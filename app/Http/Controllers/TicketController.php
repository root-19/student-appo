<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'comments']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('submitted_by', $request->user_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tickets = $query->with('comments.user')->orderBy('created_at', 'desc')->get();

        return response()->json($tickets);
    }

    public function show(string $id)
    {
        // Find by ticket_id (TICKET-1 format) instead of database ID
        $ticket = Ticket::with(['user', 'comments.user'])
            ->where('ticket_id', $id)
            ->firstOrFail();
        return response()->json($ticket);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Registrar,Administrative,Academic',
            'subcategory' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Generate ticket ID
        $lastTicket = Ticket::orderBy('id', 'desc')->first();
        $ticketNumber = $lastTicket ? (int) str_replace('TICKET-', '', $lastTicket->ticket_id) + 1 : 1;
        $ticketId = 'TICKET-' . $ticketNumber;

        $user = $request->user();
        $now = now();

        $ticketData = [
            'ticket_id' => $ticketId,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'subcategory' => $validated['subcategory'] ?? null,
            'priority' => $validated['priority'],
            'status' => 'Pending',
            'description' => $validated['description'],
            'submitted_by' => $user->id,
            'student_name' => $user->name,
            'submitted_date' => $now->toDateString(),
            'last_updated' => $now->toDateString(),
        ];

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('ticket-attachments', 'public');
            $ticketData['attachment_name'] = $file->getClientOriginalName();
            $ticketData['attachment_url'] = asset('storage/' . $path);
            $ticketData['attachment_type'] = $file->getMimeType();
        }

        $ticket = Ticket::create($ticketData);

        return response()->json($ticket, 201);
    }

    public function update(Request $request, string $id)
    {
        $ticket = Ticket::where('ticket_id', $id)->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'category' => 'sometimes|in:Registrar,Administrative,Academic',
            'subcategory' => 'nullable|string',
            'priority' => 'sometimes|in:Low,Medium,High',
            'description' => 'sometimes|string',
        ]);

        $validated['last_updated'] = now()->toDateString();
        $ticket->update($validated);

        return response()->json($ticket);
    }

    public function updateStatus(Request $request, string $id)
    {
        $ticket = Ticket::where('ticket_id', $id)->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Resolved,Rejected',
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'last_updated' => now()->toDateString(),
        ]);

        // Create notification
        Notification::create([
            'recipient_id' => $ticket->submitted_by,
            'title' => 'Ticket Status Updated',
            'message' => "Your ticket #{$ticket->ticket_id} has been marked as {$validated['status']}.",
            'type' => 'StatusUpdate',
            'timestamp' => now(),
            'ticket_id' => $ticket->ticket_id,
        ]);

        return response()->json($ticket);
    }

    public function updatePriority(Request $request, string $id)
    {
        $ticket = Ticket::where('ticket_id', $id)->firstOrFail();

        $validated = $request->validate([
            'priority' => 'required|in:Low,Medium,High',
        ]);

        $ticket->update([
            'priority' => $validated['priority'],
            'last_updated' => now()->toDateString(),
        ]);

        return response()->json($ticket);
    }

    public function setAppointment(Request $request, string $id)
    {
        $ticket = Ticket::where('ticket_id', $id)->firstOrFail();

        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable|string',
        ]);

        $ticket->update([
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'] ?? null,
            'last_updated' => now()->toDateString(),
        ]);

        // Create notification
        $timeMessage = $validated['appointment_time'] ? " at {$validated['appointment_time']}" : '';
        Notification::create([
            'recipient_id' => $ticket->submitted_by,
            'title' => 'Appointment Scheduled',
            'message' => "An appointment has been set for Ticket #{$ticket->ticket_id} on {$validated['appointment_date']}{$timeMessage}.",
            'type' => 'Appointment',
            'timestamp' => now(),
            'ticket_id' => $ticket->ticket_id,
        ]);

        return response()->json($ticket);
    }

    public function destroy(string $id)
    {
        $ticket = Ticket::where('ticket_id', $id)->firstOrFail();
        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted successfully']);
    }
}
