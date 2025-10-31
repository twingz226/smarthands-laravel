<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerFeedback;
use App\Models\FeedbackResponse;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerFeedback::with(['customer', 'employee', 'job.service'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            if ($request->rating === 'positive') {
                $query->where('overall_rating', '>=', 4);
            } elseif ($request->rating === 'negative') {
                $query->where('overall_rating', '<=', 2);
            }
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }

        $feedback = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => CustomerFeedback::count(),
            'pending' => CustomerFeedback::where('status', 'pending')->count(),
            'positive' => CustomerFeedback::where('overall_rating', '>=', 4)->count(),
            'negative' => CustomerFeedback::where('overall_rating', '<=', 2)->count(),
            'average_rating' => CustomerFeedback::avg('overall_rating') ?? 0,
        ];

        return view('admin.feedback.index', compact('feedback', 'stats', 'request'));
    }

    public function show(CustomerFeedback $feedback)
    {
        $feedback->load(['customer', 'employee', 'job.service', 'responses.respondedBy']);
        return view('admin.feedback.show', compact('feedback'));
    }

    public function respond(Request $request, CustomerFeedback $feedback)
    {
        $request->validate([
            'response_text' => 'required|string|max:1000',
            'response_type' => 'required|in:acknowledgment,resolution,follow_up',
            'is_internal_note' => 'boolean'
        ]);

        try {
            $response = FeedbackResponse::create([
                'feedback_id' => $feedback->id,
                'response_type' => $request->response_type,
                'response_text' => $request->response_text,
                'responded_by' => Auth::id(),
                'is_internal_note' => $request->boolean('is_internal_note', false)
            ]);

            // Update feedback status
            $newStatus = match($request->response_type) {
                'acknowledgment' => CustomerFeedback::STATUS_REVIEWED,
                'resolution' => CustomerFeedback::STATUS_RESOLVED,
                'follow_up' => CustomerFeedback::STATUS_RESPONDED,
            };

            $feedback->update(['status' => $newStatus]);

            Log::info('Feedback response added', [
                'feedback_id' => $feedback->id,
                'response_type' => $request->response_type,
                'responded_by' => Auth::id()
            ]);

            return back()->with('success', 'Response added successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to add feedback response: ' . $e->getMessage());
            return back()->with('error', 'Failed to add response. Please try again.');
        }
    }

    public function analytics()
    {
        // Monthly feedback trends
        $monthlyTrends = CustomerFeedback::selectRaw('
            DATE_FORMAT(created_at, "%Y-%m") as month,
            COUNT(*) as total_feedback,
            AVG(overall_rating) as avg_rating,
            COUNT(CASE WHEN overall_rating >= 4 THEN 1 END) as positive_count,
            COUNT(CASE WHEN overall_rating <= 2 THEN 1 END) as negative_count
        ')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->limit(12)
        ->get();

        // Employee performance
        $employeePerformance = CustomerFeedback::selectRaw('
            employee_id,
            COUNT(*) as feedback_count,
            AVG(overall_rating) as avg_rating,
            AVG(professionalism_rating) as avg_professionalism,
            AVG(cleanliness_rating) as avg_cleanliness
        ')
        ->whereNotNull('employee_id')
        ->with('employee')
        ->groupBy('employee_id')
        ->having('feedback_count', '>=', 3)
        ->orderBy('avg_rating', 'desc')
        ->get();

        // Rating distribution
        $ratingDistribution = CustomerFeedback::selectRaw('
            overall_rating,
            COUNT(*) as count
        ')
        ->groupBy('overall_rating')
        ->orderBy('overall_rating')
        ->get();

        return view('admin.feedback.analytics', compact(
            'monthlyTrends',
            'employeePerformance',
            'ratingDistribution'
        ));
    }
} 