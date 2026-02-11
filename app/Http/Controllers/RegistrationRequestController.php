<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = RegistrationRequest::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|unique:registration_requests',
            'student_id' => 'required|string',
            'program' => 'required|string',
            'year_level' => 'required|string',
            'section' => 'required|string',
            'password' => 'required|string|min:6',
            'document' => 'required|file|max:10240',
        ]);

        $file = $request->file('document');
        $path = $file->store('registration-documents', 'public');

        $registrationRequest = RegistrationRequest::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'student_id' => $validated['student_id'],
            'program' => $validated['program'],
            'year_level' => $validated['year_level'],
            'section' => $validated['section'],
            'password' => Hash::make($validated['password']),
            'document_name' => $file->getClientOriginalName(),
            'document_url' => asset('storage/' . $path),
            'document_type' => $file->getMimeType(),
            'status' => 'Pending',
            'date_submitted' => now()->toDateString(),
        ]);

        return response()->json($registrationRequest, 201);
    }

    public function approve(Request $request, string $id)
    {
        $registrationRequest = RegistrationRequest::findOrFail($id);

        if ($registrationRequest->status !== 'Pending') {
            return response()->json(['message' => 'Request already processed'], 400);
        }

        // Create user from registration request
        $user = User::create([
            'name' => $registrationRequest->name,
            'email' => $registrationRequest->email,
            'password' => $registrationRequest->password,
            'role' => 'Student',
            'student_id' => $registrationRequest->student_id,
            'program' => $registrationRequest->program,
            'year_level' => $registrationRequest->year_level,
            'section' => $registrationRequest->section,
            'status' => 'Active',
        ]);

        // Update registration request status
        $registrationRequest->update(['status' => 'Approved']);

        return response()->json([
            'message' => 'Registration approved and user created',
            'user' => $user,
        ]);
    }

    public function reject(Request $request, string $id)
    {
        $registrationRequest = RegistrationRequest::findOrFail($id);

        if ($registrationRequest->status !== 'Pending') {
            return response()->json(['message' => 'Request already processed'], 400);
        }

        $registrationRequest->update(['status' => 'Rejected']);

        return response()->json(['message' => 'Registration request rejected']);
    }
}
