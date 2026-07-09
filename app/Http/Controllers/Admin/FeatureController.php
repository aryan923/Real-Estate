<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.features.index', [
            'parentFeatures' => Feature::orderBy('name')->get(),
        ]);
    }

    public function data(Request $request)
    {
        $query = Feature::query()->with('parent');

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
            ->addColumn('parent', fn (Feature $feature): string => $feature->parent?->name ?? 'None')
            ->addColumn('status_label', fn (Feature $feature): string => $feature->status ? 'Active' : 'Inactive')
            ->addColumn('action', function (Feature $feature): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.features.edit', $feature) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '<form method="POST" action="' . route('admin.features.destroy', $feature) . '" class="inline-block" data-confirm="Delete this feature?">'
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
        return view('admin.pages.features.create', [
            'parentFeatures' => Feature::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFeature($request);

        $feature = Feature::create([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.features')
            ->with('status', "Feature '{$feature->name}' created successfully.");
    }

    public function edit(Feature $feature): View
    {
        return view('admin.pages.features.edit', [
            'feature' => $feature,
            'parentFeatures' => Feature::where('id', '!=', $feature->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Feature $feature): RedirectResponse
    {
        $validated = $this->validateFeature($request, $feature);

        $feature->update([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.features')
            ->with('status', "Feature '{$feature->name}' updated successfully.");
    }

    public function destroy(Feature $feature): RedirectResponse
    {
        $feature->delete();

        return redirect()
            ->route('admin.features')
            ->with('status', "Feature '{$feature->name}' deleted successfully.");
    }

    private function validateFeature(Request $request, ?Feature $feature = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('features', 'name')->ignore($feature?->id),
            ],
            'parent_id' => array_filter([
                'nullable',
                'integer',
                Rule::exists('features', 'id'),
                $feature ? Rule::notIn([$feature->id]) : null,
            ]),
            'status' => ['required', 'boolean'],
        ]);
    }
}
