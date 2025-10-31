<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    // Store message from homepage form
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string',
        ]);
        
        // Create the contact message
        ContactMessage::create($validated);
        
        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your message has been submitted!'
            ]);
        }
        
        // Return with success message for regular form submission
        return back()->with('success', 'Your message has been submitted!');
    }

    // Admin: List all submissions
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $unreadCount = ContactMessage::where('read', false)->count();
            return response()->json(['unread_count' => $unreadCount]);
        }
        $messages = ContactMessage::orderByDesc('created_at')->paginate(20);
        // Mark all messages as read when viewing the list
        ContactMessage::where('read', false)->update(['read' => true]);
        return view('admin.contact_messages.index', compact('messages'));
    }

    // Admin: Show single message
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        // Mark message as read when viewing
        if (!$message->read) {
            $message->read = true;
            $message->save();
        }
        return view('admin.contact_messages.show', compact('message'));
    }

    // Admin: Delete message
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        return redirect()->route('admin.contact_messages.index')->with('success', 'Message deleted.');
    }
    
    // Mark all messages as read
    public function markAllRead(Request $request)
    {
        ContactMessage::where('read', false)->update(['read' => true]);
        return response()->json(['success' => true]);
    }
}
