<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ImageuploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.categories.index', [
            'parentCategories' => Category::orderBy('name')->get(),
        ]);
    }

    public function data(Request $request)
    {
        $query = Category::query()->with('parent');

        if ($request->filled('parent_filter') && $request->string('parent_filter')->toString() !== 'all') {
            $parentFilter = $request->string('parent_filter')->toString();

            if ($parentFilter === 'null') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $parentFilter);
            }
        }

        if ($request->filled('status_filter') && in_array($request->string('status_filter')->toString(), ['active', 'inactive'], true)) {
            $query->where('status', $request->string('status_filter')->toString() === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('parent', fn (Category $category): string => $category->parent?->name ?? 'None')
            ->addColumn('status_label', fn (Category $category): string => $category->status ? 'Active' : 'Inactive')
            ->addColumn('action', function (Category $category): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.categories.edit', $category) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '<form method="POST" action="' . route('admin.categories.destroy', $category) . '" class="inline-block" data-confirm="Delete this category?">'
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
        return view('admin.pages.categories.create', [
            'parentCategories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCategory($request);

        $category = Category::create([
            'name' => $validated['name'],
            'image' => $this->storeImage($request),
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.categories')
            ->with('status', "Category '{$category->name}' created successfully.");
    }

    public function edit(Category $category): View
    {
        return view('admin.pages.categories.edit', [
            'category' => $category,
            'parentCategories' => Category::where('id', '!=', $category->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $this->validateCategory($request, $category);

        $payload = [
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => $validated['status'],
        ];

        if ($image = $this->storeImage($request)) {
            $this->deleteImage($category->image);
            $payload['image'] = $image;
        }

        $category->update($payload);

        return redirect()
            ->route('admin.categories')
            ->with('status', "Category '{$category->name}' updated successfully.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.categories')
            ->with('status', "Category '{$category->name}' deleted successfully.");
    }

    private function validateCategory(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category?->id),
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'parent_id' => array_filter([
                'nullable',
                'integer',
                Rule::exists('categories', 'id'),
                $category ? Rule::notIn([$category->id]) : null,
            ]),
            'status' => ['required', 'boolean'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return ImageuploadHelper::upload($request->file('image'), 'categories');
    }

    private function deleteImage(?string $path): void
    {
        if (! $path || str_starts_with($path, ['http://', 'https://', '/'])) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
