<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, string $ticketId)
    {
        // Find by ticket_id (TICKET-1 format) instead of database ID
        $ticket = Ticket::where('ticket_id', $ticketId)->firstOrFail();

        $validated = $request->validate([
            'text' => 'required|string',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $user = $request->user();

        $commentData = [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'role' => $user->role,
            'text' => $validated['text'],
            'timestamp' => now(),
        ];

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('comment-attachments', 'public');
            $commentData['attachment_name'] = $file->getClientOriginalName();
            $commentData['attachment_url'] = asset('storage/' . $path);
            $commentData['attachment_type'] = $file->getMimeType();
        }

        $comment = Comment::create($commentData);

        // Update ticket last_updated
        $ticket->update(['last_updated' => now()->toDateString()]);

        // Create notification if commenter is not the ticket owner
        if ($user->id !== $ticket->submitted_by) {
            Notification::create([
                'recipient_id' => $ticket->submitted_by,
                'title' => 'New Comment',
                'message' => "{$user->role} {$user->name} commented on ticket #{$ticket->ticket_id}.",
                'type' => 'NewComment',
                'timestamp' => now(),
                'ticket_id' => $ticket->ticket_id,
            ]);
        }

        return response()->json($comment->load('user'), 201);
    }

    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
