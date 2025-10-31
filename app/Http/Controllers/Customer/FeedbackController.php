<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CustomerFeedback as Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Auth::user()->feedbacks()->with('booking')->latest()->get();
        return view('customer.feedback.index', compact('feedbacks'));
    }

    public function create(Booking $booking)
    {
        // Verify the booking belongs to the user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if feedback already exists for this booking
        if ($booking->feedback) {
            return redirect()->route('customer.feedback.edit', $booking->feedback);
        }

        return view('customer.feedback.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        // Verify the booking belongs to the user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $booking->feedback()->create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('customer.my_bookings')
            ->with('success', 'Thank you for your feedback!');
    }

    public function edit(Feedback $feedback)
    {
        // Verify the feedback belongs to the user
        if ($feedback->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.feedback.edit', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        // Verify the feedback belongs to the user
        if ($feedback->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $feedback->update($validated);

        return redirect()->route('customer.feedback.index')
            ->with('success', 'Feedback updated successfully!');
    }

    public function destroy(Feedback $feedback)
    {
        // Verify the feedback belongs to the user
        if ($feedback->user_id !== Auth::id()) {
            abort(403);
        }

        $feedback->delete();

        return back()->with('success', 'Feedback deleted successfully!');
    }
}
