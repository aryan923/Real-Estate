<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $documents = Document::orderBy('created_at', 'desc')->get();
        
        // Group documents by category
        $groupedDocs = $documents->groupBy('category');

        $customers = Customer::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $bookings = Booking::orderBy('booking_no')->get();

        return view('admin.pages.documents.index', compact(
            'groupedDocs', 'customers', 'projects', 'bookings'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|in:PAN,Aadhaar,Agreement,Receipt',
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'file' => 'required|file|max:10240',
        ]);

        $type = $validated['documentable_type'];
        $modelClass = match ($type) {
            'customer' => Customer::class,
            'project' => Project::class,
            'booking' => Booking::class,
            default => null
        };

        if (!$modelClass || !$modelClass::find($validated['documentable_id'])) {
            return redirect()->back()->with('error', 'Invalid association details provided.');
        }

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        
        $path = $file->store('documents', 'public');

        Document::create([
            'documentable_type' => $modelClass,
            'documentable_id' => $validated['documentable_id'],
            'file_name' => $fileName,
            'file_path' => '/storage/' . $path,
            'file_size' => $fileSize,
            'category' => $validated['category'],
        ]);

        return redirect()->route('admin.documents.index')->with('success', 'Document uploaded successfully.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        if ($document->file_path) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $document->file_path));
        }

        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Document deleted successfully.');
    }
}
