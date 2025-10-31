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
    
   
}