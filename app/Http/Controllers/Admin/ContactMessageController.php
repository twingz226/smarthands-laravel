<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the contact messages.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $unreadCount = ContactMessage::where('read', false)->count();
            return response()->json(['unread_count' => $unreadCount]);
        }
        
        $messages = ContactMessage::orderByDesc('created_at')
            ->paginate(20);
            
        // Mark all messages as read when viewing the list
        ContactMessage::where('read', false)->update(['read' => true]);
        
        return view('admin.contact_messages.index', compact('messages'));
    }

    /**
     * Display the specified contact message.
     */
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

    /**
     * Soft delete the specified message.
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete(); // This will now do a soft delete
        
        return redirect()->route('admin.contact_messages.index')
            ->with('success', 'Message moved to trash.');
    }
    
    /**
     * Restore the specified soft-deleted message.
     */
    public function restore($id)
    {
        $message = ContactMessage::onlyTrashed()->findOrFail($id);
        $message->restore();
        
        return redirect()->route('admin.trash.index')
            ->with('success', 'Message restored successfully.');
    }
    
    /**
     * Permanently delete the specified message.
     */
    public function forceDelete($id)
    {
        $message = ContactMessage::onlyTrashed()->findOrFail($id);
        $message->forceDelete();
        
        return redirect()->route('admin.trash.index')
            ->with('success', 'Message permanently deleted.');
    }
    
    /**
     * Mark all messages as read.
     */
    public function markAllRead(Request $request)
    {
        ContactMessage::where('read', false)->update(['read' => true]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'All messages marked as read.');
    }
}
