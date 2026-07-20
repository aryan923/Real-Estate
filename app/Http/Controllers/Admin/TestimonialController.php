<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ImageuploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.testimonials.index');
    }

    public function data(Request $request)
    {
        $query = Testimonial::query();

        if ($request->filled('status_filter') && in_array($request->string('status_filter')->toString(), ['active', 'inactive'], true)) {
            $query->where('status', $request->string('status_filter')->toString() === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('status_label', function (Testimonial $testimonial): string {
                return $testimonial->status
                    ? '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #10b981; color: #ffffff;">Active</span>'
                    : '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #6b7280; color: #ffffff;">Inactive</span>';
            })
            ->addColumn('featured_label', fn (Testimonial $testimonial): string => $testimonial->is_featured ? 'Yes' : 'No')
            ->addColumn('action', function (Testimonial $testimonial): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.testimonials.edit', $testimonial) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.testimonials.destroy', $testimonial) . '" class="inline-block" data-confirm="Delete this testimonial?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action', 'status_label'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.pages.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        $testimonial = Testimonial::create([
            'name' => $validated['name'],
            'designation' => $validated['designation'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'image' => $this->storeImage($request),
            'status' => $validated['status'],
            'is_featured' => $validated['is_featured'] ?? false,
        ]);

        return redirect()
            ->route('admin.testimonials')
            ->with('status', "Testimonial for '{$testimonial->name}' created successfully.");
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.pages.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $this->validateTestimonial($request, $testimonial);

        $payload = [
            'name' => $validated['name'],
            'designation' => $validated['designation'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'status' => $validated['status'],
            'is_featured' => $validated['is_featured'] ?? false,
        ];

        if ($request->hasFile('image')) {
            $this->deleteImage($testimonial->image);
            $payload['image'] = $this->storeImage($request);
        }

        $testimonial->update($payload);

        return redirect()
            ->route('admin.testimonials')
            ->with('status', "Testimonial for '{$testimonial->name}' updated successfully.");
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $name = $testimonial->name;
        $this->deleteImage($testimonial->image);
        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials')
            ->with('status', "Testimonial for '{$name}' deleted successfully.");
    }

    private function validateTestimonial(Request $request, ?Testimonial $testimonial = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return ImageuploadHelper::upload($request->file('image'), 'testimonials');
    }

    private function deleteImage(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://', '/'])) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
