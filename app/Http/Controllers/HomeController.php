<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProperties = Property::with(['images', 'categories'])
            ->where('status', 'active')
            ->where('featured', true)
            ->inRandomOrder()
            ->take(2)
            ->get();

        if ($featuredProperties->count() < 2) {
            $featuredProperties = Property::with(['images', 'categories'])
                ->where('status', 'active')
                ->inRandomOrder()
                ->take(2)
                ->get();
        }

        $rentProperties = Property::with(['images', 'categories'])
            ->where('status', 'active')
            ->where('property_purpose', 'rent')
            ->latest()
            ->take(12)
            ->get();

        if ($rentProperties->count() < 12) {
            $rentProperties = Property::with(['images', 'categories'])
                ->where('status', 'active')
                ->latest()
                ->take(12)
                ->get();
        }

        $categories = Category::query()
            ->where('status', true)
            ->where('is_featured', true)
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'active');
            }])
            ->take(6)
            ->get();

        $cities = City::query()
            ->where('is_featured', true)
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderByDesc('properties_count')
            ->take(12)
            ->get();

        $latestProperties = Property::with(['images', 'categories', 'city'])
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $testimonials = Testimonial::query()
            ->where('status', true)
            ->where('is_featured', true)
            ->inRandomOrder()
            ->take(6)
            ->get();

        $adProperty = Property::with(['images', 'city', 'state', 'country'])
            ->where('status', 'active')
            ->where('is_advertisement', true)
            ->inRandomOrder()
            ->first();

        if (!$adProperty) {
            $adProperty = Property::with(['images', 'city', 'state', 'country'])
                ->where('status', 'active')
                ->inRandomOrder()
                ->first();
        }

        $featuredAgents = User::query()
            ->has('agentMeta')
            ->with('agentMeta')
            ->whereHas('agentMeta', function ($q) {
                $q->where('is_featured', true);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('index', compact('featuredProperties', 'rentProperties', 'categories', 'cities', 'latestProperties', 'testimonials', 'adProperty', 'featuredAgents'));
    }

    public function about(): View
    {
        $page = \App\Models\Page::where('slug', 'about-us')->first();
        
        $team = User::query()
            ->has('agentMeta')
            ->with('agentMeta')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($user) {
                $listingsCount = Property::where('builder_developer', $user->name)
                    ->where('status', 'active')
                    ->count();
                return [
                    'name' => $user->name,
                    'member_id' => $user->agentMeta?->mls_id ?? 'N/A',
                    'bio' => $user->agentMeta?->about_me ?? '',
                    'image' => \App\Classes\ImageuploadHelper::getUploadedFileUrl($user->profile_pic),
                    'listings' => $listingsCount,
                    'facebook' => $user->agentMeta?->facebook ?? '#',
                    'twitter' => $user->agentMeta?->twitter ?? '#',
                    'linkedin' => $user->agentMeta?->linkedin ?? '#',
                    'instagram' => $user->agentMeta?->instagram ?? '#',
                    'youtube' => $user->agentMeta?->youtube ?? '#',
                    'vimeo' => $user->agentMeta?->vimeo ?? '#',
                    'whatsapp' => $user->agentMeta?->whatsapp ?? '#',
                    'phone' => $user->phone ?? '',
                    'email' => $user->email ?? '',
                ];
            });

        $testimonials = Testimonial::query()
            ->where('status', true)
            ->where('is_featured', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('about-us', compact('page', 'team', 'testimonials'));
    }

    public function contact(): View
    {
        return view('contact-us', [
            'settings' => \App\Models\Setting::allAsArray(),
        ]);
    }

    public function showCmsPage(string $slug): View
    {
        $page = \App\Models\Page::where('slug', $slug)->firstOrFail();

        $team = User::query()
            ->has('agentMeta')
            ->with('agentMeta')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($user) {
                $listingsCount = Property::where('builder_developer', $user->name)
                    ->where('status', 'active')
                    ->count();
                return [
                    'name' => $user->name,
                    'member_id' => $user->agentMeta?->mls_id ?? 'N/A',
                    'bio' => $user->agentMeta?->about_me ?? '',
                    'image' => \App\Classes\ImageuploadHelper::getUploadedFileUrl($user->profile_pic),
                    'listings' => $listingsCount,
                    'facebook' => $user->agentMeta?->facebook ?? '#',
                    'twitter' => $user->agentMeta?->twitter ?? '#',
                    'linkedin' => $user->agentMeta?->linkedin ?? '#',
                    'instagram' => $user->agentMeta?->instagram ?? '#',
                    'youtube' => $user->agentMeta?->youtube ?? '#',
                    'vimeo' => $user->agentMeta?->vimeo ?? '#',
                    'whatsapp' => $user->agentMeta?->whatsapp ?? '#',
                    'phone' => $user->phone ?? '',
                    'email' => $user->email ?? '',
                ];
            });

        $testimonials = Testimonial::query()
            ->where('status', true)
            ->where('is_featured', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('about-us', compact('page', 'team', 'testimonials'));
    }

    public function listProperty(): View
    {
        return view('list-property', [
            'settings' => \App\Models\Setting::allAsArray(),
            'categories' => \App\Models\Category::orderBy('name')->get(),
            'features' => \App\Models\Feature::orderBy('name')->get(),
            'countries' => \App\Models\Country::orderBy('name')->get(),
        ]);
    }

    public function getStates(\App\Models\Country $country): JsonResponse
    {
        return response()->json($country->states()->orderBy('name')->get(['id', 'name']));
    }

    public function getCities(\App\Models\State $state): JsonResponse
    {
        return response()->json($state->cities()->orderBy('name')->get(['id', 'name']));
    }

    public function storeListProperty(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Submitter Info
            'submitter_name' => ['required', 'string', 'max:255'],
            'submitter_email' => ['required', 'email', 'max:255'],
            'submitter_phone' => ['nullable', 'string', 'max:50'],
            'listing_type' => ['required', \Illuminate\Validation\Rule::in(['owner', 'agent', 'builder'])],

            // Property Overview
            'title' => ['required', 'string', 'max:255'],
            'property_purpose' => ['required', \Illuminate\Validation\Rule::in(['sale', 'rent', 'lease'])],
            'category_id' => ['nullable', 'array'],
            'category_id.*' => ['integer', \Illuminate\Validation\Rule::exists('categories', 'id')],
            'price' => ['nullable', 'string', 'max:255'],
            'price_type' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:255'],

            // Location
            'address_line' => ['nullable', 'string', 'max:255'],
            'locality' => ['nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'integer', \Illuminate\Validation\Rule::exists('countries', 'id')],
            'state_id' => ['nullable', 'integer', \Illuminate\Validation\Rule::exists('states', 'id')],
            'city_id' => ['nullable', 'integer', \Illuminate\Validation\Rule::exists('cities', 'id')],
            'zip' => ['nullable', 'string', 'max:50'],

            // Specs
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'rooms' => ['nullable', 'integer', 'min:0'],
            'year_built' => ['nullable', 'integer', 'digits:4'],

            // Media & Features
            'feature_id' => ['nullable', 'array'],
            'feature_id.*' => ['integer', \Illuminate\Validation\Rule::exists('features', 'id')],
            'primary_image_file' => ['nullable', 'image', 'max:10240'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['file', 'image', 'max:10240'],
            'floor_plan_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm', 'max:51200'],
        ]);

        try {
            return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $validated) {
                $slug = \Illuminate\Support\Str::slug($validated['title']) . '-' . uniqid();

                $videoPath = null;
                if ($request->hasFile('video_file')) {
                    $videoPath = \App\Classes\ImageuploadHelper::upload($request->file('video_file'), 'properties/videos');
                }

                $floorPlanPath = null;
                if ($request->hasFile('floor_plan_file')) {
                    $floorPlanPath = \App\Classes\ImageuploadHelper::upload($request->file('floor_plan_file'), 'properties/floor-plans');
                }

                // Create Property under status 'external_draft'
                $property = \App\Models\Property::create([
                    'title' => $validated['title'],
                    'slug' => $slug,
                    'description' => $request->input('description'),
                    'price' => $validated['price'] ?? null,
                    'price_type' => $validated['price_type'] ?? null,
                    'status' => 'external_draft',
                    'featured' => false,
                    'property_purpose' => $validated['property_purpose'] ?? null,
                    'listing_type' => $validated['listing_type'] ?? null,
                    'bedrooms' => $validated['bedrooms'] ?? null,
                    'bathrooms' => $validated['bathrooms'] ?? null,
                    'rooms' => $validated['rooms'] ?? null,
                    'size' => $validated['size'] ?? null,
                    'year_built' => $validated['year_built'] ?? null,
                    'video_url' => $videoPath,
                    'floor_plan_url' => $floorPlanPath,
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
                    $primaryPath = \App\Classes\ImageuploadHelper::upload($request->file('primary_image_file'), 'properties/primary');
                    \App\Models\PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $primaryPath,
                        'is_primary' => true,
                        'sort_order' => 0,
                    ]);
                }

                if ($request->hasFile('gallery_images')) {
                    foreach ($request->file('gallery_images') as $index => $galleryImage) {
                        if ($galleryImage && $galleryImage->isValid()) {
                            $galleryPath = \App\Classes\ImageuploadHelper::upload($galleryImage, 'properties/gallery');
                            \App\Models\PropertyImage::create([
                                
                                'property_id' => $property->id,
                                'path' => $galleryPath,
                                'is_primary' => false,
                                'sort_order' => $index + 1,
                            ]);
                        }
                    }
                }

                // Create Enquiry under type 'property_submit'
                \App\Models\Enquiry::create([
                    'type' => 'property_submit',
                    'enq_for' => $validated['property_purpose'],
                    'name' => $validated['submitter_name'],
                    'email' => $validated['submitter_email'],
                    'phone' => $validated['submitter_phone'] ?? null,
                    'message' => $request->input('description') ?? "External property listing submission for: " . $property->title,
                    'property_id' => $property->id,
                    'status' => 'new',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Property submitted successfully! It is currently pending review by our administrator.'
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save property submission. ' . $e->getMessage()
            ], 500);
        }
    }
}
