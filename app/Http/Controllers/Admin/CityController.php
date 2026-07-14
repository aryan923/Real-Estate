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
            ->addColumn('featured_label', fn (City $city): string => $city->is_featured ? 'Yes' : 'No')
            ->addColumn('action', function (City $city): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.cities.edit', $city) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '</div>';
            })
            ->rawColumns(['action'])
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
