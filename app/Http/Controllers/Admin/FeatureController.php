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
            ->addColumn('status_label', function (Feature $feature): string {
                return $feature->status
                    ? '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #10b981; color: #ffffff;">Active</span>'
                    : '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #6b7280; color: #ffffff;">Inactive</span>';
            })
            ->addColumn('action', function (Feature $feature): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.features.edit', $feature) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.features.destroy', $feature) . '" class="inline-block" data-confirm="Delete this feature?">'
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
                Rule::unique('features', 'name')
                    ->ignore($feature?->id)
                    ->where('parent_id', $request->input('parent_id'))
                    ->whereNull('deleted_at'),
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
