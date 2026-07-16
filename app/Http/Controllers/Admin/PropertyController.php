<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Feature;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Classes\ImageuploadHelper;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $query = Property::query()->with(['categories', 'city', 'state', 'country', 'images']);

        if ($request->filled('category_filter') && $request->string('category_filter')->toString() !== 'all') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->string('category_filter')->toString());
            });
        }

        if ($request->filled('status_filter') && in_array($request->string('status_filter')->toString(), ['active', 'pending', 'sold', 'draft', 'external_draft'], true)) {
            $query->where('status', $request->string('status_filter')->toString());
        }

        return DataTables::eloquent($query)
            ->addColumn('image', function (Property $property): string {
                $primaryImage = $property->images->firstWhere('is_primary', true);
                if (!$primaryImage) {
                    return '<span class="text-xs text-gray-500">No image</span>';
                }

                return '<img src="' . e(ImageuploadHelper::getUploadedFileUrl($primaryImage->path)) . '" class="h-12 w-20 rounded-lg object-cover" alt="Primary image">';
            })
            ->addColumn('category', fn(Property $property): string => $property->categories->pluck('name')->implode(', ') ?: 'None')
            ->addColumn('location', fn(Property $property): string => trim(implode(', ', array_filter([$property->city?->name, $property->state?->name, $property->country?->name]))) ?: 'Unknown')
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
            ->editColumn('price', fn (Property $property): string => is_numeric($property->price) ? format_price($property->price) : ($property->price ?: 'Contact for price'))
            ->rawColumns(['image', 'action'])
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

        try {
            return DB::transaction(function () use ($request, $validated) {
                $slug = !empty($validated['slug'])
                    ? Str::slug($validated['slug'])
                    : Str::slug($validated['title']) . '-' . uniqid();

                $videoPath = null;
                if ($request->hasFile('video_file')) {
                    $videoPath = ImageuploadHelper::upload($request->file('video_file'), 'properties/videos');
                }

                $floorPlanPath = null;
                if ($request->hasFile('floor_plan_file')) {
                    $floorPlanPath = ImageuploadHelper::upload($request->file('floor_plan_file'), 'properties/floor-plans');
                }

                $property = Property::create([
                    'title' => $validated['title'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'] ?? null,
                    'price_type' => $validated['price_type'] ?? null,
                    'price_info' => $validated['price_info'] ?? null,
                    'status' => $validated['status'],
                    'featured' => $validated['featured'] ?? false,
                    'is_advertisement' => $validated['is_advertisement'] ?? false,
                    'property_code' => $validated['property_code'] ?? null,
                    'property_purpose' => $validated['property_purpose'] ?? null,
                    'listing_type' => $validated['listing_type'] ?? null,
                    'RERA_no' => $validated['RERA_no'] ?? null,
                    'builder_developer' => $validated['builder_developer'] ?? null,
                    'bedrooms' => $validated['bedrooms'] ?? null,
                    'bathrooms' => $validated['bathrooms'] ?? null,
                    'rooms' => $validated['rooms'] ?? null,
                    'size' => $validated['size'] ?? null,
                    'garage_size' => $validated['garage_size'] ?? null,
                    'year_built' => $validated['year_built'] ?? null,
                    'available_from' => $validated['available_from'] ?? null,
                    'exterior_material' => $validated['exterior_material'] ?? null,
                    'structure_type' => $validated['structure_type'] ?? null,
                    'floors' => $validated['floors'] ?? null,
                    'video_url' => $videoPath,
                    'floor_plan_url' => $floorPlanPath,
                    'map_latitude' => $validated['map_latitude'] ?? null,
                    'map_longitude' => $validated['map_longitude'] ?? null,
                    'address_line' => $validated['address_line'] ?? null,
                    'city_id' => $validated['city_id'] ?? null,
                    'locality' => $validated['locality'] ?? null,
                    'state_id' => $validated['state_id'] ?? null,
                    'zip' => $validated['zip'] ?? null,
                    'country_id' => $validated['country_id'] ?? null,
                ]);

                $property->categories()->sync($validated['category_id'] ?? []);
                $property->features()->sync($validated['feature_id'] ?? []);

                if ($request->hasFile('primary_image_file')) {
                    $primaryPath = ImageuploadHelper::upload($request->file('primary_image_file'), 'properties/primary');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $primaryPath,
                        'is_primary' => true,
                        'sort_order' => 0,
                    ]);
                }

                if ($request->hasFile('gallery_images')) {
                    foreach ($request->file('gallery_images') as $index => $galleryImage) {
                        if ($galleryImage && $galleryImage->isValid()) {
                            $galleryPath = ImageuploadHelper::upload($galleryImage, 'properties/gallery');
                            PropertyImage::create([
                                'property_id' => $property->id,
                                'path' => $galleryPath,
                                'is_primary' => false,
                                'sort_order' => $index + 1,
                            ]);
                        }
                    }
                }

                return redirect()
                    ->route('admin.properties')
                    ->with('status', "Property '{$property->title}' created successfully.");
            });
        } catch (\Throwable $exception) {
            return back()
                ->withInput()
                ->with('transaction_error', 'Unable to save property: ' . $exception->getMessage());
        }
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

        try {
            return DB::transaction(function () use ($request, $validated, $property) {
                $slug = !empty($validated['slug'])
                    ? Str::slug($validated['slug'])
                    : Str::slug($validated['title']) . '-' . $property->id;

                $videoPath = $property->video_url;
                if ($request->hasFile('video_file')) {
                    if (!empty($property->video_url)) {
                        Storage::disk('public')->delete($property->video_url);
                    }
                    $videoPath = ImageuploadHelper::upload($request->file('video_file'), 'properties/videos');
                }

                $floorPlanPath = $property->floor_plan_url;
                if ($request->hasFile('floor_plan_file')) {
                    if (!empty($property->floor_plan_url)) {
                        Storage::disk('public')->delete($property->floor_plan_url);
                    }
                    $floorPlanPath = ImageuploadHelper::upload($request->file('floor_plan_file'), 'properties/floor-plans');
                }

                $property->update([
                    'title' => $validated['title'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'] ?? null,
                    'price_type' => $validated['price_type'] ?? null,
                    'price_info' => $validated['price_info'] ?? null,
                    'status' => $validated['status'],
                    'featured' => $validated['featured'] ?? false,
                    'is_advertisement' => $validated['is_advertisement'] ?? false,
                    'property_code' => $validated['property_code'] ?? null,
                    'property_purpose' => $validated['property_purpose'] ?? null,
                    'listing_type' => $validated['listing_type'] ?? null,
                    'RERA_no' => $validated['RERA_no'] ?? null,
                    'builder_developer' => $validated['builder_developer'] ?? null,
                    'bedrooms' => $validated['bedrooms'] ?? null,
                    'bathrooms' => $validated['bathrooms'] ?? null,
                    'rooms' => $validated['rooms'] ?? null,
                    'size' => $validated['size'] ?? null,
                    'garage_size' => $validated['garage_size'] ?? null,
                    'year_built' => $validated['year_built'] ?? null,
                    'available_from' => $validated['available_from'] ?? null,
                    'exterior_material' => $validated['exterior_material'] ?? null,
                    'structure_type' => $validated['structure_type'] ?? null,
                    'floors' => $validated['floors'] ?? null,
                    'video_url' => $videoPath,
                    'floor_plan_url' => $floorPlanPath,
                    'map_latitude' => $validated['map_latitude'] ?? null,
                    'map_longitude' => $validated['map_longitude'] ?? null,
                    'address_line' => $validated['address_line'] ?? null,
                    'city_id' => $validated['city_id'] ?? null,
                    'locality' => $validated['locality'] ?? null,
                    'state_id' => $validated['state_id'] ?? null,
                    'zip' => $validated['zip'] ?? null,
                    'country_id' => $validated['country_id'] ?? null,
                ]);

                $property->categories()->sync($validated['category_id'] ?? []);
                $property->features()->sync($validated['feature_id'] ?? []);

                if ($request->hasFile('primary_image_file')) {
                    PropertyImage::where('property_id', $property->id)->where('is_primary', true)->delete();
                    $primaryPath = ImageuploadHelper::upload($request->file('primary_image_file'), 'properties/primary');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $primaryPath,
                        'is_primary' => true,
                        'sort_order' => 0,
                    ]);
                }

                if ($request->filled('removed_gallery_ids')) {
                    $removedIds = array_filter($request->input('removed_gallery_ids', []), fn ($id) => is_numeric($id));
                    if (!empty($removedIds)) {
                        $imagesToDelete = PropertyImage::where('property_id', $property->id)
                            ->whereIn('id', $removedIds)
                            ->get();

                        foreach ($imagesToDelete as $image) {
                            if (!empty($image->path)) {
                                Storage::disk('public')->delete($image->path);
                            }
                            $image->delete();
                        }
                    }
                }

                if ($request->hasFile('gallery_images')) {
                    foreach ($request->file('gallery_images') as $index => $galleryImage) {
                        if ($galleryImage && $galleryImage->isValid()) {
                            $galleryPath = ImageuploadHelper::upload($galleryImage, 'properties/gallery');
                            PropertyImage::create([
                                'property_id' => $property->id,
                                'path' => $galleryPath,
                                'is_primary' => false,
                                'sort_order' => $index + 1,
                            ]);
                        }
                    }
                }

                return redirect()
                    ->route('admin.properties')
                    ->with('status', "Property '{$property->title}' updated successfully.");
            });
        } catch (\Throwable $exception) {
            return back()
                ->withInput()
                ->with('transaction_error', 'Unable to update property: ' . $exception->getMessage());
        }
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
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('properties', 'slug')->ignore($property?->id)->whereNull('deleted_at')],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'string', 'max:255'],
            'price_type' => ['nullable', 'string', 'max:50'],
            'price_info' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'pending', 'sold', 'draft', 'external_draft'])],
            'featured' => ['nullable', 'boolean'],
            'is_advertisement' => ['nullable', 'boolean'],
            'category_id' => ['nullable', 'array'],
            'category_id.*' => ['integer', Rule::exists('categories', 'id')],
            'feature_id' => ['nullable', 'array'],
            'feature_id.*' => ['integer', Rule::exists('features', 'id')],
            'primary_image_file' => ['nullable', 'image', 'max:20480'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['file', 'image', 'max:20480'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'rooms' => ['nullable', 'integer', 'min:0'],
            'property_code' => ['nullable', 'string', 'max:255'],
            'property_purpose' => ['nullable', Rule::in(['sale', 'rent', 'lease'])],
            'listing_type' => ['nullable', Rule::in(['owner', 'agent', 'builder'])],
            'RERA_no' => ['nullable', 'string', 'max:255'],
            'builder_developer' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'garage_size' => ['nullable', 'string', 'max:255'],
            'year_built' => ['nullable', 'integer', 'digits:4'],
            'available_from' => ['nullable', 'date'],
            'exterior_material' => ['nullable', 'string', 'max:255'],
            'structure_type' => ['nullable', 'string', 'max:255'],
            'floors' => ['nullable', 'integer', 'min:0'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm', 'max:512000'],
            'floor_plan_url' => ['nullable', 'string', 'max:255'],
            'floor_plan_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'map_latitude' => ['nullable', 'numeric'],
            'map_longitude' => ['nullable', 'numeric'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'city_id' => ['nullable', 'integer', Rule::exists('cities', 'id')],
            'locality' => ['nullable', 'string', 'max:255'],
            'state_id' => ['nullable', 'integer', Rule::exists('states', 'id')],
            'zip' => ['nullable', 'string', 'max:50'],
            'country_id' => ['nullable', 'integer', Rule::exists('countries', 'id')],
        ]);
    }
}
