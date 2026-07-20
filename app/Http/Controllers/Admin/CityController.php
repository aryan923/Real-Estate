<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.cities.index');
    }

    public function data(Request $request)
    {
        $query = City::query()->with(['state', 'country']);

        if ($request->filled('featured_filter')) {
            $featuredFilter = $request->string('featured_filter')->toString();
            if ($featuredFilter === 'yes') {
                $query->where('is_featured', true);
            } elseif ($featuredFilter === 'no') {
                $query->where('is_featured', false);
            }
        }

        return DataTables::eloquent($query)
            ->addColumn('state_name', fn (City $city): string => $city->state?->name ?? 'N/A')
            ->addColumn('country_name', fn (City $city): string => $city->country?->name ?? 'N/A')
            ->addColumn('featured_label', function (City $city): string {
                return $city->is_featured
                    ? '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #3b82f6; color: #ffffff;">Yes</span>'
                    : '<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #6b7280; color: #ffffff;">No</span>';
            })
            ->addColumn('action', function (City $city): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.cities.edit', $city) . '" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white" title="Edit">'
                    . '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
                    . '</a>'
                    . '</div>';
            })
            ->rawColumns(['action', 'featured_label'])
            ->toJson();
    }

    public function edit(City $city): View
    {
        return view('admin.pages.cities.edit', [
            'city' => $city,
        ]);
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $validated = $request->validate([
            'is_featured' => ['required', 'boolean'],
        ]);

        $city->update([
            'is_featured' => $validated['is_featured'],
        ]);

        return redirect()
            ->route('admin.cities')
            ->with('status', "City '{$city->name}' updated successfully.");
    }
}
