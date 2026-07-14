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
            ->addColumn('status_label', fn (Testimonial $testimonial): string => $testimonial->status ? 'Active' : 'Inactive')
            ->addColumn('featured_label', fn (Testimonial $testimonial): string => $testimonial->is_featured ? 'Yes' : 'No')
            ->addColumn('action', function (Testimonial $testimonial): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.testimonials.edit', $testimonial) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '<form method="POST" action="' . route('admin.testimonials.destroy', $testimonial) . '" class="inline-block" data-confirm="Delete this testimonial?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">Delete</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action'])
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
