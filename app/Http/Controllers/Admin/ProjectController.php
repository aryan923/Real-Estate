<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.projects.index');
    }

    public function data(Request $request)
    {
        $query = Project::query()->withCount([
            'properties as sold_count' => function ($q) {
                $q->where('inventory_status', 'sold');
            },
            'properties as available_count' => function ($q) {
                $q->where('inventory_status', 'available');
            },
            'properties as reserved_count' => function ($q) {
                $q->where('inventory_status', 'reserved');
            }
        ]);

        return DataTables::eloquent($query)
            ->addColumn('action', function (Project $project): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.projects.show', $project) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="View Detail">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>'
                    . '</a>'
                    . '<a href="' . route('admin.projects.edit', $project) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '<form method="POST" action="' . route('admin.projects.destroy', $project) . '" class="inline-block" data-confirm="Delete this project?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Delete">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                    . '</button>'
                    . '</form>'
                    . '</div>';
            })
            ->editColumn('status', function (Project $project): string {
                $status = $project->status;
                $label = ucfirst(str_replace('_', ' ', $status));

                switch ($status) {
                    case 'completed':
                        return '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide text-white bg-emerald-500">' . $label . '</span>';
                    case 'under_construction':
                        return '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide text-white bg-amber-500">' . $label . '</span>';
                    case 'planning':
                        return '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide text-white bg-blue-500">' . $label . '</span>';
                    default: // 'cancelled'
                        return '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide text-white bg-gray-500">' . $label . '</span>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.pages.projects.form', [
            'mode' => 'create',
            'project' => null
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'total_units' => 'required|integer|min:0',
            'status' => 'required|in:planning,under_construction,completed,cancelled',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $properties = $project->properties()->get();

        $unitsCount = $properties->count();
        $soldCount = $properties->where('inventory_status', 'sold')->count();
        $availableCount = $properties->where('inventory_status', 'available')->count();
        $reservedCount = $properties->where('inventory_status', 'reserved')->count();
        $blockedCount = $properties->where('inventory_status', 'blocked')->count();

        $towers = $properties->pluck('tower')->filter()->unique()->values();
        
        return view('admin.pages.projects.show', compact(
            'project', 'properties', 'unitsCount', 'soldCount', 
            'availableCount', 'reservedCount', 'blockedCount', 'towers'
        ));
    }

    public function edit(Project $project): View
    {
        return view('admin.pages.projects.form', [
            'mode' => 'edit',
            'project' => $project
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,' . $project->id,
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'total_units' => 'required|integer|min:0',
            'status' => 'required|in:planning,under_construction,completed,cancelled',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
