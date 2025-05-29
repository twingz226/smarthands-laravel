<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary counts
        $customerCount = Customer::count();
        $activeJobCount = Job::whereIn('status', ['assigned', 'in_progress'])->count();
        $cleanerCount = Employee::count();
        $pendingBookingCount = Booking::where('status', 'pending')->count();

        // Job status counts
        $jobStatusCounts = [
            'pending' => Job::where('status', 'pending')->count(),
            'assigned' => Job::where('status', 'assigned')->count(),
            'in_progress' => Job::where('status', 'in_progress')->count(),
            'completed' => Job::where('status', 'completed')->count(),
            'cancelled' => Job::where('status', 'cancelled')->count(),
        ];

        // Monthly revenue for the last 6 months (with fallback to created_at if completed_at is null)
        $monthlyRevenue = Job::where('status', 'completed')
            ->join('services', 'jobs.service_id', '=', 'services.id')
            ->selectRaw('
                DATE_FORMAT(COALESCE(jobs.completed_at, jobs.created_at), "%b %Y") as month, 
                SUM(services.price) as revenue
            ')
            ->where('jobs.status', 'completed')
            ->where(function($query) {
                $query->where('jobs.completed_at', '>=', now()->subMonths(6))
                      ->orWhereNull('jobs.completed_at');
            })
            ->groupBy('month')
            ->orderByRaw('MIN(COALESCE(jobs.completed_at, jobs.created_at))')
            ->get();

        // Recent jobs (limit to latest 5)
        $recentJobs = Job::with(['customer', 'service'])
            ->latest()
            ->take(5)
            ->get();

        // Recent bookings (limit to latest 5)
        $recentBookings = Booking::with(['customer', 'service'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'customerCount',
            'activeJobCount',
            'cleanerCount',
            'pendingBookingCount',
            'jobStatusCounts',
            'monthlyRevenue',
            'recentJobs',
            'recentBookings'
        ));
    }

    /**
     * Temporary endpoint to backfill completed_at dates (run once after migration)
     */
    public function backfillCompletedDates()
    {
        $updated = DB::table('jobs')
            ->where('status', 'completed')
            ->whereNull('completed_at')
            ->update(['completed_at' => DB::raw('updated_at')]);
            
        return "Backfilled $updated completed jobs with completion dates";
    }
}