<?php

namespace App\Http\Controllers;

use App\Models\CustomerFeedback;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function show($token)
    {
        $job = Job::where('rating_token', $token)->first();
        
        if (!$job) {
            return redirect()->route('home')->with('error', 'Invalid feedback link.');
        }

        // Check if feedback already exists
        $existingFeedback = CustomerFeedback::where('job_id', $job->id)->first();
        
        if ($existingFeedback) {
            return redirect()->route('home')->with('info', 'Feedback has already been submitted for this job.');
        }

        return view('feedback.form', compact('job'));
    }

    public function store(Request $request, $token)
    {
        $job = Job::where('rating_token', $token)->first();
        
        if (!$job) {
            return redirect()->route('home')->with('error', 'Invalid feedback link.');
        }

        // Check if feedback already exists
        $existingFeedback = CustomerFeedback::where('job_id', $job->id)->first();
        
        if ($existingFeedback) {
            return redirect()->route('home')->with('info', 'Feedback has already been submitted for this job.');
        }

        $request->validate([
            'overall_rating' => 'required|integer|between:1,5',
            'cleanliness_rating' => 'nullable|integer|between:1,5',
            'professionalism_rating' => 'nullable|integer|between:1,5',
            'punctuality_rating' => 'nullable|integer|between:1,5',
            'communication_rating' => 'nullable|integer|between:1,5',
            'value_rating' => 'nullable|integer|between:1,5',
            'comments' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean'
        ]);

        try {
            $feedback = CustomerFeedback::create([
                'job_id' => $job->id,
                'customer_id' => $job->customer_id,
                'employee_id' => $job->employees->first()?->id,
                'overall_rating' => $request->overall_rating,
                'cleanliness_rating' => $request->cleanliness_rating,
                'professionalism_rating' => $request->professionalism_rating,
                'punctuality_rating' => $request->punctuality_rating,
                'communication_rating' => $request->communication_rating,
                'value_rating' => $request->value_rating,
                'comments' => $request->comments,
                'is_anonymous' => $request->boolean('is_anonymous', false),
                'feedback_type' => CustomerFeedback::TYPE_POST_SERVICE,
                'status' => CustomerFeedback::STATUS_PENDING
            ]);

            Log::info('Customer feedback submitted', [
                'job_id' => $job->id,
                'customer_id' => $job->customer_id,
                'overall_rating' => $request->overall_rating,
                'is_anonymous' => $request->boolean('is_anonymous', false)
            ]);

            return view('feedback.thank_you', compact('feedback'));

        } catch (\Exception $e) {
            Log::error('Failed to submit feedback: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit feedback. Please try again.');
        }
    }
} 