<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Feature;
use App\Models\Property;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PropertyController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.properties', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function data(Request $request)
    {
        $query = Property::query()->with(['category', 'city', 'state', 'country']);

        if ($request->filled('category_filter') && $request->string('category_filter')->toString() !== 'all') {
            $query->where('category_id', $request->string('category_filter')->toString());
        }

        if ($request->filled('status_filter') && in_array($request->string('status_filter')->toString(), ['active', 'pending', 'sold', 'draft'], true)) {
            $query->where('status', $request->string('status_filter')->toString());
        }

        return DataTables::eloquent($query)
            ->addColumn('category', fn (Property $property): string => $property->category?->name ?? 'None')
            ->addColumn('location', fn (Property $property): string => trim(implode(', ', array_filter([$property->city?->name, $property->state?->name, $property->country?->name]))) ?: 'Unknown')
            ->addColumn('action', function (Property $property): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.properties.edit', $property) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '<form method="POST" action="' . route('admin.properties.destroy', $property) . '" class="inline-block" data-confirm="Delete this property?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">Delete</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function statesByCountry(Country $country): JsonResponse
    {
        return response()->json($country->states()->orderBy('name')->get(['id', 'name']));
    }

    public function citiesByState(State $state): JsonResponse
    {
        return response()->json($state->cities()->orderBy('name')->get(['id', 'name']));
    }

    public function create(): View
    {
        $selectedCountry = old('country_id', null);
        $selectedState = old('state_id', null);

        return view('admin.pages.properties-form', [
            'mode' => 'create',
            'property' => null,
            'categories' => Category::with('children')->whereNull('parent_id')->orderBy('name')->get(),
            'features' => Feature::with('children')->whereNull('parent_id')->orderBy('name')->get(),
            'countries' => Country::orderBy('name')->get(),
            'states' => $selectedCountry ? State::where('country_id', $selectedCountry)->select('id', 'name')->orderBy('name')->get() : collect(),
            'cities' => $selectedState ? City::where('state_id', $selectedState)->select('id', 'name')->orderBy('name')->get() : collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProperty($request);

        $property = Property::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . uniqid(),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'price_type' => $validated['price_type'] ?? null,
            'price_info' => $validated['price_info'] ?? null,
            'status' => $validated['status'],
            'featured' => $validated['featured'] ?? false,
            'property_code' => $validated['property_code'] ?? null,
            'property_purpose' => $validated['property_purpose'] ?? null,
            'listing_type' => $validated['listing_type'] ?? null,
            'RERA_no' => $validated['RERA_no'] ?? null,
            'builder_developer' => $validated['builder_developer'] ?? null,
            'category_id' => is_array($validated['category_id'] ?? null) ? head($validated['category_id']) : ($validated['category_id'] ?? null),
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'rooms' => $validated['rooms'] ?? null,
            'size' => $validated['size'] ?? null,
            'lot_size' => $validated['lot_size'] ?? null,
            'garage_size' => $validated['garage_size'] ?? null,
            'year_built' => $validated['year_built'] ?? null,
            'available_from' => $validated['available_from'] ?? null,
            'exterior_material' => $validated['exterior_material'] ?? null,
            'structure_type' => $validated['structure_type'] ?? null,
            'floors' => $validated['floors'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'virtual_tour_url' => $validated['virtual_tour_url'] ?? null,
            'map_latitude' => $validated['map_latitude'] ?? null,
            'map_longitude' => $validated['map_longitude'] ?? null,
            'address_line' => $validated['address_line'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'area' => $validated['area'] ?? null,
            'state_id' => $validated['state_id'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'country_id' => $validated['country_id'] ?? null,
        ]);

        $property->categories()->sync($validated['category_id'] ?? []);
        $property->features()->sync($validated['feature_id'] ?? []);

        return redirect()
            ->route('admin.properties')
            ->with('status', "Property '{$property->title}' created successfully.");
    }

    public function edit(Property $property): View
    {
        $selectedCountry = old('country_id', $property->country_id);
        $selectedState = old('state_id', $property->state_id);

        return view('admin.pages.properties-form', [
            'mode' => 'edit',
            'property' => $property,
            'categories' => Category::with('children')->whereNull('parent_id')->orderBy('name')->get(),
            'features' => Feature::with('children')->whereNull('parent_id')->orderBy('name')->get(),
            'countries' => Country::orderBy('name')->get(),
            'states' => $selectedCountry ? State::where('country_id', $selectedCountry)->select('id', 'name')->orderBy('name')->get() : collect(),
            'cities' => $selectedState ? City::where('state_id', $selectedState)->select('id', 'name')->orderBy('name')->get() : collect(),
        ]);
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $validated = $this->validateProperty($request, $property);

        $property->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . $property->id,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'price_type' => $validated['price_type'] ?? null,
            'price_info' => $validated['price_info'] ?? null,
            'status' => $validated['status'],
            'featured' => $validated['featured'] ?? false,
            'property_code' => $validated['property_code'] ?? null,
            'property_purpose' => $validated['property_purpose'] ?? null,
            'listing_type' => $validated['listing_type'] ?? null,
            'RERA_no' => $validated['RERA_no'] ?? null,
            'builder_developer' => $validated['builder_developer'] ?? null,
            'category_id' => is_array($validated['category_id'] ?? null) ? head($validated['category_id']) : ($validated['category_id'] ?? null),
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'rooms' => $validated['rooms'] ?? null,
            'size' => $validated['size'] ?? null,
            'lot_size' => $validated['lot_size'] ?? null,
            'garage_size' => $validated['garage_size'] ?? null,
            'year_built' => $validated['year_built'] ?? null,
            'available_from' => $validated['available_from'] ?? null,
            'exterior_material' => $validated['exterior_material'] ?? null,
            'structure_type' => $validated['structure_type'] ?? null,
            'floors' => $validated['floors'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'virtual_tour_url' => $validated['virtual_tour_url'] ?? null,
            'map_latitude' => $validated['map_latitude'] ?? null,
            'map_longitude' => $validated['map_longitude'] ?? null,
            'address_line' => $validated['address_line'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'area' => $validated['area'] ?? null,
            'state_id' => $validated['state_id'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'country_id' => $validated['country_id'] ?? null,
        ]);

        $property->categories()->sync($validated['category_id'] ?? []);
        $property->features()->sync($validated['feature_id'] ?? []);

        return redirect()
            ->route('admin.properties')
            ->with('status', "Property '{$property->title}' updated successfully.");
    }

    public function destroy(Property $property): RedirectResponse
    {
        $property->delete();

        return redirect()
            ->route('admin.properties')
            ->with('status', "Property '{$property->title}' deleted successfully.");
    }

    private function validateProperty(Request $request, ?Property $property = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'string', 'max:255'],
            'price_type' => ['nullable', 'string', 'max:50'],
            'price_info' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'pending', 'sold', 'draft'])],
            'featured' => ['nullable', 'boolean'],
            'category_id' => ['nullable', 'array'],
            'category_id.*' => ['integer', Rule::exists('categories', 'id')],
            'feature_id' => ['nullable', 'array'],
            'feature_id.*' => ['integer', Rule::exists('features', 'id')],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'rooms' => ['nullable', 'integer', 'min:0'],
            'property_code' => ['nullable', 'string', 'max:255'],
            'property_purpose' => ['nullable', Rule::in(['sale', 'rent', 'lease'])],
            'listing_type' => ['nullable', Rule::in(['owner', 'agent', 'builder'])],
            'RERA_no' => ['nullable', 'string', 'max:255'],
            'builder_developer' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'lot_size' => ['nullable', 'string', 'max:255'],
            'garage_size' => ['nullable', 'string', 'max:255'],
            'year_built' => ['nullable', 'integer', 'digits:4'],
            'available_from' => ['nullable', 'date'],
            'exterior_material' => ['nullable', 'string', 'max:255'],
            'structure_type' => ['nullable', 'string', 'max:255'],
            'floors' => ['nullable', 'integer', 'min:0'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'virtual_tour_url' => ['nullable', 'url', 'max:255'],
            'map_latitude' => ['nullable', 'numeric'],
            'map_longitude' => ['nullable', 'numeric'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'city_id' => ['nullable', 'integer', Rule::exists('cities', 'id')],
            'area' => ['nullable', 'string', 'max:255'],
            'state_id' => ['nullable', 'integer', Rule::exists('states', 'id')],
            'zip' => ['nullable', 'string', 'max:50'],
            'country_id' => ['nullable', 'integer', Rule::exists('countries', 'id')],
        ]);
    }
}
