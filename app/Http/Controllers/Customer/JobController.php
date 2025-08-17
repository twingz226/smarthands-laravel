<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Show the rating form for a completed job
     */
    public function showRatingForm(Job $job)
    {
        // Check if job belongs to the authenticated customer
        if ($job->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if job is completed and not yet rated
        if ($job->status !== 'completed') {
            return redirect()->route('customer.jobs.index')
                ->with('error', 'You can only rate completed jobs.');
        }

        if ($job->rating()->exists()) {
            return redirect()->route('customer.jobs.index')
                ->with('error', 'You have already rated this job.');
        }

        return view('customer.jobs.rate', compact('job'));
    }

    /**
     * Submit rating for a job
     */
    public function submitRating(Request $request, Job $job)
    {
        // Validate job ownership and status
        if ($job->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($job->status !== 'completed') {
            return redirect()->route('customer.jobs.index')
                ->with('error', 'You can only rate completed jobs.');
        }

        if ($job->rating()->exists()) {
            return redirect()->route('customer.jobs.index')
                ->with('error', 'You have already rated this job.');
        }

        // Validate request
        $validated = $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Create ratings for each employee
            foreach ($validated['ratings'] as $employeeId => $rating) {
                $rating = new Rating([
                    'rating' => $rating,
                    'comments' => $validated['comments'],
                    'customer_id' => auth()->id(),
                    'employee_id' => $employeeId
                ]);

                $job->ratings()->save($rating);
            }

            DB::commit();

            // Send notification to admin and cleaners
            // TODO: Implement notifications

            return redirect()->route('customer.jobs.index')
                ->with('success', 'Thank you for your feedback!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while saving your ratings. Please try again.');
        }
    }
} 