<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('user');

        // Filter by status
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $documents = $query->orderBy('created_at', 'desc')->get();

        return response()->json($documents);
    }

    public function show(string $id)
    {
        $document = Document::with('user')->findOrFail($id);
        return response()->json($document);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $user = $request->user();
        $file = $request->file('file');

        $path = $file->store('documents', 'public');
        $size = $file->getSize();
        $sizeFormatted = $this->formatBytes($size);

        $document = Document::create([
            'name' => $validated['name'],
            'type' => $file->getMimeType(),
            'size' => $sizeFormatted,
            'upload_date' => now()->toDateString(),
            'status' => 'Pending',
            'user_id' => $user->id,
            'student_name' => $user->name,
            'file_path' => $path,
        ]);

        return response()->json($document, 201);
    }

    public function updateStatus(Request $request, string $id)
    {
        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $document->update(['status' => $validated['status']]);

        return response()->json($document);
    }

    public function destroy(string $id)
    {
        $document = Document::findOrFail($id);
        
        // Delete file if exists
        if ($document->file_path && \Storage::disk('public')->exists($document->file_path)) {
            \Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json(['message' => 'Document deleted successfully']);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
