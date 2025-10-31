<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicRatingController extends Controller
{
    /**
     * Show the rating form
     */
    public function showForm($ratingToken)
    {
        $job = Job::where('rating_token', $ratingToken)
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if job is already rated
        if ($job->ratings()->exists()) {
            return view('public.ratings.form', [
                'job' => $job,
                'alreadyRated' => true
            ]);
        }

        return view('public.ratings.form', compact('job'));
    }

    /**
     * Handle the rating submission
     */
    public function submitRating(Request $request, $ratingToken)
    {
        $job = Job::where('rating_token', $ratingToken)
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if job is already rated
        if ($job->ratings()->exists()) {
            return back()->with('error', 'This job has already been rated.');
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
            foreach ($validated['ratings'] as $employeeId => $ratingValue) {
                $ratingRecord = new Rating([
                    'rating' => $ratingValue,
                    'comments' => $validated['comments'] ?? null,
                    'customer_id' => $job->customer_id,
                    'employee_id' => $employeeId
                ]);

                $job->ratings()->save($ratingRecord);
            }

            DB::commit();
            return back()->with('success', 'Thank you for your feedback!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Rating submission failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while saving your ratings. Please try again.');
        }
    }
} 