<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Rating;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    // Customer List Report
    public function customerList()
    {
        $customers = Customer::withCount('jobs')
            ->withMax('jobs', 'scheduled_date')
            ->orderBy('name')
            ->get();
            
        return view('admin.reports.customers.list', compact('customers'));
    }
    
    
    // Customer Cleaning History Report
    public function customerHistory()
    {
        $customers = Customer::has('jobs')
            ->with(['jobs' => function($query) {
                $query->where('status', 'completed')
                    ->with(['service', 'employees']);
            }])
            ->paginate(10);
            
        return view('admin.reports.customers.history', compact('customers'));
    }
    
    // Customer Feedback/Ratings Report
    public function customerFeedback(Request $request)
    {
        $query = Rating::with(['customer', 'employee', 'job.service'])
            ->latest();
            
        // Apply filters
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Get paginated results
        $ratings = $query->paginate(10);
        
        // Calculate statistics
        $averageRating = Rating::avg('rating');
        $totalFeedback = Rating::count();
        $recentFeedback = Rating::where('created_at', '>=', now()->subDays(30))->count();
        
        return view('admin.reports.customers.feedback', compact(
            'ratings',
            'averageRating',
            'totalFeedback',
            'recentFeedback'
        ));
    }
    
    // Customer Retention Report
    public function customerRetention()
    {
        // Calculate retention metrics
        $totalCustomers = Customer::count();
        $repeatCustomers = Customer::has('jobs', '>', 1)->count();
        $newCustomersLastMonth = Customer::where('created_at', '>=', now()->subMonth())->count();
        
        // Customer jobs distribution
        $customersByJobCount = [
            '1 job' => Customer::has('jobs', '=', 1)->count(),
            '2-5 jobs' => Customer::has('jobs', '>', 1)->has('jobs', '<=', 5)->count(),
            '6+ jobs' => Customer::has('jobs', '>', 5)->count(),
        ];
        
        // Get top repeat customers with their booking info
        $topCustomers = Customer::withCount('jobs')
            ->has('jobs', '>', 1)
            ->with([
                'jobs' => function($query) {
                    $query->select('customer_id', 'created_at')
                        ->orderBy('created_at');
                }
            ])
            ->orderBy('jobs_count', 'desc')
            ->take(20)
            ->get()
            ->map(function($customer) {
                $customer->first_booking = optional($customer->jobs->first())->created_at?->format('Y-m-d') ?? 'N/A';
                $customer->last_booking = optional($customer->jobs->last())->created_at?->format('Y-m-d') ?? 'N/A';
                return $customer;
            });
        
        return view('admin.reports.customers.retention', compact(
            'totalCustomers',
            'repeatCustomers',
            'newCustomersLastMonth',
            'customersByJobCount',
            'topCustomers'
        ));
    }
    
    // Job Completion Report
    public function jobCompletion(Request $request)
    {
        $query = Job::where('status', 'completed')
            ->with(['customer', 'service', 'employees', 'rating']);
            
        // Apply filters
        if ($request->filled('start_date')) {
            $query->where('completed_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('completed_at', '<=', $request->end_date);
        }
        
        $jobs = $query->latest('completed_at')->paginate(15);
        
        // Completion stats for chart
        $completionStats = [
            'completed' => Job::where('status', 'completed')->count(),
            'pending' => Job::where('status', 'pending')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];
        
        // Cleaner ratings for chart
        $cleanerRatings = Employee::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->get()
            ->map(function ($employee) {
                $employee->ratings_avg_rating = $employee->ratings_avg_rating ?? 0;
                return $employee;
            });
        
        return view('admin.reports.jobs.completion', compact(
            'jobs',
            'completionStats',
            'cleanerRatings',
            'request'
        ));
    }
    
    // Export Customer List to PDF
    public function exportCustomerListPDF()
    {
        $customers = Customer::withCount('jobs')
            ->withMax('jobs', 'scheduled_date')
            ->orderBy('name')
            ->get();
            
        $pdf = PDF::loadView('admin.reports.customers.pdf', compact('customers'));
        return $pdf->download('customer-list-' . date('Y-m-d') . '.pdf');
    }
    
    // Export Customer List to CSV
    public function exportCustomerListCSV(): StreamedResponse
    {
        $customers = Customer::withCount('jobs')
            ->withMax('jobs', 'scheduled_date')
            ->orderBy('name')
            ->get();
            
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customer-list-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Name', 'Email', 'Contact', 'Total Jobs', 'Last Service Date']);
            
            // CSV Data
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->name,
                    $customer->email,
                    $customer->contact,
                    $customer->jobs_count,
                    $customer->jobs_max_scheduled_date ? date('Y-m-d', strtotime($customer->jobs_max_scheduled_date)) : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    // Export Customer List to Excel (CSV format that Excel can open)
    public function exportCustomerListExcel(): StreamedResponse
    {
        return $this->exportCustomerListCSV();
    }

    // Export Cleaning History to PDF
    public function exportCleaningHistoryPDF()
    {
        $jobs = Job::with(['customer', 'service', 'employees'])
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->get();
            
        $pdf = PDF::loadView('admin.reports.cleaning-history-pdf', compact('jobs'));
        return $pdf->download('cleaning-history-' . date('Y-m-d') . '.pdf');
    }
    
    // Export Cleaning History to CSV
    public function exportCleaningHistoryCSV(): StreamedResponse
    {
        $jobs = Job::with(['customer', 'service', 'employees'])
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->get();
            
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="cleaning-history-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($jobs) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Customer', 'Service', 'Employee', 'Date', 'Status']);
            
            // CSV Data
            foreach ($jobs as $job) {
                $employeeNames = $job->employees->pluck('name')->implode(', ');
                fputcsv($file, [
                    $job->customer->name,
                    $job->service->name,
                    $employeeNames,
                    $job->completed_at ? $job->completed_at->format('Y-m-d H:i') : 'N/A',
                    ucfirst($job->status)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    // Export Cleaning History to Excel
    public function exportCleaningHistoryExcel(): StreamedResponse
    {
        return $this->exportCleaningHistoryCSV();
    }

    // Export Customer Feedback to CSV
    public function exportCustomerFeedbackCSV(Request $request): StreamedResponse
    {
        $query = Rating::with(['customer', 'employee', 'job.service'])
            ->latest();
            
        // Apply filters
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $ratings = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customer-feedback-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($ratings) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Date', 'Customer', 'Service', 'Cleaner', 'Rating', 'Feedback']);
            
            // CSV Data
            foreach ($ratings as $rating) {
                fputcsv($file, [
                    $rating->created_at->format('M d, Y'),
                    $rating->customer->name ?? 'N/A',
                    $rating->job->service->name ?? 'N/A',
                    $rating->employee->name ?? 'N/A',
                    $rating->rating,
                    $rating->comments ?? 'No feedback provided'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Export Customer Feedback to PDF
    public function exportCustomerFeedbackPDF(Request $request)
    {
        $query = Rating::with(['customer', 'employee', 'job.service'])
            ->latest();
            
        // Apply filters
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $ratings = $query->get();
        
        $pdf = PDF::loadView('admin.reports.customers.feedback-pdf', compact('ratings'));
        return $pdf->download('customer-feedback-' . date('Y-m-d') . '.pdf');
    }

    // Export Customer Feedback to Excel
    public function exportCustomerFeedbackExcel(Request $request): StreamedResponse
    {
        return $this->exportCustomerFeedbackCSV($request);
    }

    // Export Customer Retention to CSV
    public function exportCustomerRetentionCSV(): StreamedResponse
    {
        $topCustomers = Customer::withCount('jobs')
            ->has('jobs', '>', 1)
            ->with([
                'jobs' => function($query) {
                    $query->select('customer_id', 'created_at')
                        ->orderBy('created_at');
                }
            ])
            ->orderBy('jobs_count', 'desc')
            ->take(20)
            ->get()
            ->map(function($customer) {
                $customer->first_booking = optional($customer->jobs->first())->created_at?->format('Y-m-d') ?? 'N/A';
                $customer->last_booking = optional($customer->jobs->last())->created_at?->format('Y-m-d') ?? 'N/A';
                return $customer;
            });
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customer-retention-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($topCustomers) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Customer', 'Total Bookings', 'First Booking', 'Last Booking', 'Booking Frequency']);
            
            // CSV Data
            foreach ($topCustomers as $customer) {
                $frequency = 'N/A';
                if ($customer->first_booking != 'N/A' && $customer->last_booking != 'N/A') {
                    $first = \Carbon\Carbon::parse($customer->first_booking);
                    $last = \Carbon\Carbon::parse($customer->last_booking);
                    $daysBetween = $first->diffInDays($last);
                    $frequency = $daysBetween > 0 ? round($customer->jobs_count / ($daysBetween/30), 1) . ' jobs/month' : $customer->jobs_count . ' jobs/month';
                }
                
                fputcsv($file, [
                    $customer->name,
                    $customer->jobs_count,
                    $customer->first_booking,
                    $customer->last_booking,
                    $frequency
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Export Customer Retention to PDF
    public function exportCustomerRetentionPDF()
    {
        $topCustomers = Customer::withCount('jobs')
            ->has('jobs', '>', 1)
            ->with([
                'jobs' => function($query) {
                    $query->select('customer_id', 'created_at')
                        ->orderBy('created_at');
                }
            ])
            ->orderBy('jobs_count', 'desc')
            ->take(20)
            ->get()
            ->map(function($customer) {
                $customer->first_booking = optional($customer->jobs->first())->created_at?->format('Y-m-d') ?? 'N/A';
                $customer->last_booking = optional($customer->jobs->last())->created_at?->format('Y-m-d') ?? 'N/A';
                return $customer;
            });
        
        $pdf = PDF::loadView('admin.reports.customers.retention-pdf', compact('topCustomers'));
        return $pdf->download('customer-retention-' . date('Y-m-d') . '.pdf');
    }

    // Export Customer Retention to Excel
    public function exportCustomerRetentionExcel(): StreamedResponse
    {
        return $this->exportCustomerRetentionCSV();
    }

    // Export Job Completion to CSV
    public function exportJobCompletionCSV(Request $request): StreamedResponse
    {
        $query = Job::where('status', 'completed')
            ->with(['customer', 'service', 'employees', 'rating']);
            
        // Apply filters
        if ($request->filled('start_date')) {
            $query->where('completed_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('completed_at', '<=', $request->end_date);
        }
        
        $jobs = $query->latest('completed_at')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="job-completion-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($jobs) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Customer Name', 'Service', 'Cleaner Assigned', 'Date Completed', 'Rating', 'Status']);
            
            // CSV Data
            foreach ($jobs as $job) {
                $employeeNames = $job->employees->pluck('name')->implode(', ') ?: 'Not Assigned';
                $rating = $job->rating ? number_format($job->rating->rating, 1) : 'No rating';
                
                fputcsv($file, [
                    $job->customer->name ?? 'N/A',
                    $job->service->name ?? 'N/A',
                    $employeeNames,
                    $job->completed_at ? $job->completed_at->format('M d, Y') : 'N/A',
                    $rating,
                    ucfirst($job->status)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Export Job Completion to PDF
    public function exportJobCompletionPDF(Request $request)
    {
        $query = Job::where('status', 'completed')
            ->with(['customer', 'service', 'employees', 'rating']);
            
        // Apply filters
        if ($request->filled('start_date')) {
            $query->where('completed_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('completed_at', '<=', $request->end_date);
        }
        
        $jobs = $query->latest('completed_at')->get();
        
        $pdf = PDF::loadView('admin.reports.jobs.completion-pdf', compact('jobs'));
        return $pdf->download('job-completion-' . date('Y-m-d') . '.pdf');
    }

    // Export Job Completion to Excel
    public function exportJobCompletionExcel(Request $request): StreamedResponse
    {
        return $this->exportJobCompletionCSV($request);
    }
}