@extends('layouts.master')

@section('title', 'Property Detail')

@section('content')
    @php
        $primaryImage = $property->images?->firstWhere('is_primary', true) ?? $property->images?->first();
        $galleryImages = $property->images?->sortBy('sort_order')->values() ?? collect();
        $fallbackImage = 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80';
        $mainImageUrl = $primaryImage
            ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path)
            : $fallbackImage;
        $thumbnailImages = $galleryImages->isNotEmpty() ? $galleryImages : collect([(object) ['path' => $primaryImage?->path ?? null]]);
        $location = trim(implode(', ', array_filter([
            $property->address_line,
            $property->locality,
            $property->city?->name,
            $property->state?->name,
            $property->country?->name,
        ]))) ?: 'Location not set';
        $mapSrc = $property->map_latitude && $property->map_longitude
            ? 'https://www.google.com/maps?q=' . $property->map_latitude . ',' . $property->map_longitude . '&z=13&output=embed'
            : 'https://www.google.com/maps?q=' . rawurlencode($location) . '&z=13&output=embed';
        $priceText = $property->price ?: 'Contact for price';
        $priceInfo = $property->price_info ?: null;
        $propertyType = $property->property_purpose ? \Illuminate\Support\Str::title($property->property_purpose) : 'Property';
        $selectedFeatures = $property->features?->loadMissing('parent')->values() ?? collect();
        $groupedFeatures = $selectedFeatures->groupBy(function ($feature) {
            return $feature->parent_id ?: $feature->id;
        });
        $featureNames = $property->features?->pluck('name')->filter()->values() ?? collect();
        $propertyCategories = $property->categories ?? collect();
        $categoryGroups = $propertyCategories->groupBy(function ($category) {
            return $category->parent?->name ?: $category->name;
        });
        $videoUrl = filled($property->video_url) ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($property->video_url) : null;
        $floorPlanUrl = filled($property->floor_plan_url) ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($property->floor_plan_url) : null;
    @endphp

    <header class="relative isolate overflow-visible bg-[#0f355e]" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>
    </header>

    <section class="bg-[#f3f5f7] py-8 sm:py-10">
        <div class="section-shell max-w-[980px]">
            <div class="flex flex-wrap items-center gap-2 text-[13px] font-medium text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-[#1e73be]">Home</a>
                <span>›</span>
                <a href="{{ route('properties') }}" class="hover:text-[#1e73be]">Properties</a>
                <span>›</span>
                <span class="text-[#1e73be]">{{ $property->title }}</span>
            </div>

            <div class="mt-4 flex flex-wrap items-start justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="rounded bg-[#2f7ecc] px-2.5 py-1 text-xs font-semibold text-white">{{ $propertyType }}</span>
                        @if ($property->featured)
                            <span class="rounded bg-[#2f7ecc] px-2.5 py-1 text-xs font-semibold text-white">Featured</span>
                        @endif
                    </div>
                    <h1 class="mt-3 text-[42px] font-extrabold leading-[1.08] tracking-[-0.02em] text-[#1f2a44]">{{ $property->title }}</h1>
                    <p class="mt-2 inline-flex items-center gap-1.5 text-[15px] text-slate-600">
                        <span>📍</span>
                        {{ $location }}
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-[18px] font-semibold leading-none text-[#1e73be]">{{ $priceInfo ?: $priceText }}</p>
                    <p class="mt-2 text-[36px] font-extrabold leading-none text-[#0f6fcb]">{{ $priceText }}</p>
                    <div class="mt-4 flex flex-wrap justify-end gap-2">
                        <button type="button" class="inline-flex items-center gap-1.5 rounded bg-white px-3 py-2 text-[13px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50">↗ Share</button>
                        <button type="button" class="inline-flex items-center gap-1.5 rounded bg-white px-3 py-2 text-[13px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50">♡ Favorite</button>
                        <button type="button" class="inline-flex items-center gap-1.5 rounded bg-white px-3 py-2 text-[13px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50">🖨 Print</button>
                    </div>
                </div>
            </div>

            <div class="mt-5 grid gap-3 lg:grid-cols-[minmax(0,1fr)_118px]">
                <div class="relative overflow-hidden rounded-lg border border-slate-300 bg-white">
                    <img id="property-main-image" src="{{ $mainImageUrl }}" alt="{{ $property->title }}" class="h-[460px] w-full object-cover">

                    <button type="button" id="property-prev" class="absolute left-2 top-1/2 inline-flex h-12 w-8 -translate-y-1/2 items-center justify-center rounded bg-black/45 text-white hover:bg-black/60">‹</button>
                    <button type="button" id="property-next" class="absolute right-2 top-1/2 inline-flex h-12 w-8 -translate-y-1/2 items-center justify-center rounded bg-black/45 text-white hover:bg-black/60">›</button>
                    <span class="absolute right-3 top-3 rounded bg-[#1e73be] px-4 py-1.5 text-sm font-semibold text-white">Active</span>

                </div>

                <div id="property-thumbs" class="grid max-h-[460px] gap-1 overflow-y-auto rounded-lg border border-slate-300 bg-white p-1">
                    @foreach ($thumbnailImages as $index => $image)
                        @php
                            $thumbUrl = $image->path ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($image->path) : $fallbackImage;
                        @endphp
                        <button type="button" class="property-thumb overflow-hidden rounded border-2 {{ $index === 0 ? 'is-active border-[#1e73be]' : 'border-transparent' }}" data-image="{{ $thumbUrl }}"><img src="{{ $thumbUrl }}" alt="thumb {{ $index + 1 }}" class="h-16 w-full object-cover"></button>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px] lg:items-start">
                <div class="space-y-6">
                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <h2 class="section-title">Description</h2>
                        <div class="mt-4 space-y-4 text-[15px] leading-7 text-slate-600">
                            <p>{{ $property->description ?: 'No description provided for this property yet.' }}</p>
                        </div>
                    </article>

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]" data-property-accordion-item>
                        <button type="button" class="property-info-accordion-toggle flex w-full items-center justify-between gap-4 text-left" data-property-accordion-toggle aria-expanded="true" aria-controls="property-address-panel">
                            <h2 class="section-title">Address</h2>
                            <svg xmlns="http://www.w3.org/2000/svg" class="property-info-accordion-icon h-5 w-5 shrink-0 text-[#1e73be] transition-transform rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="property-address-panel" class="property-info-accordion-panel">
                            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 text-[15px]">
                                <p><span class="font-semibold text-slate-900">Address:</span> <span class="text-slate-600">{{ $property->address_line ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">City:</span> <span class="text-slate-600">{{ $property->city?->name ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Area:</span> <span class="text-slate-600">{{ $property->locality ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">State/County:</span> <span class="text-slate-600">{{ $property->state?->name ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Zip:</span> <span class="text-slate-600">{{ $property->zip ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Country:</span> <span class="text-slate-600">{{ $property->country?->name ?: 'N/A' }}</span></p>
                            </div>

                            <div class="mt-5">
                                <a href="https://google.com/maps?q={{ urlencode($location) }}" target="_blank" rel="noopener" class="inline-flex items-center rounded bg-[#67c47c] px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-[#56b36b]">
                                    Open In Google Maps
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]" data-property-accordion-item>
                        <button type="button" class="property-info-accordion-toggle flex w-full items-center justify-between gap-4 text-left" data-property-accordion-toggle aria-expanded="false" aria-controls="property-details-panel">
                            <h2 class="section-title">Details</h2>
                            <svg xmlns="http://www.w3.org/2000/svg" class="property-info-accordion-icon h-5 w-5 shrink-0 text-[#1e73be] transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="property-details-panel" class="property-info-accordion-panel">
                            <div class="mt-6 grid gap-x-8 gap-y-3 md:grid-cols-2 lg:grid-cols-3 text-[15px]">
                                <p><span class="font-semibold text-slate-900">Property Id:</span> <span class="text-slate-600">{{ $property->id }}</span></p>
                                <p><span class="font-semibold text-slate-900">Price:</span> <span class="text-slate-600">{{ $priceText }}</span></p>
                                <p><span class="font-semibold text-slate-900">Price Info:</span> <span class="text-slate-600">{{ $priceInfo ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Property Size:</span> <span class="text-slate-600">{{ $property->size ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Rooms:</span> <span class="text-slate-600">{{ $property->rooms ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Bedrooms:</span> <span class="text-slate-600">{{ $property->bedrooms ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Bathrooms:</span> <span class="text-slate-600">{{ $property->bathrooms ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Year Built:</span> <span class="text-slate-600">{{ $property->year_built ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Garage Size:</span> <span class="text-slate-600">{{ $property->garage_size ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Available from:</span> <span class="text-slate-600">{{ optional($property->available_from)->format('Y-m-d') ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Exterior Material:</span> <span class="text-slate-600">{{ $property->exterior_material ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Structure Type:</span> <span class="text-slate-600">{{ $property->structure_type ?: 'N/A' }}</span></p>
                                <p><span class="font-semibold text-slate-900">Floors No:</span> <span class="text-slate-600">{{ $property->floors ?: 'N/A' }}</span></p>
                            </div>

                            @if ($categoryGroups->isNotEmpty())
                                <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white">
                                    <div class="flex items-center justify-between border-b border-slate-200 bg-white/80 px-4 py-3 backdrop-blur">
                                        <p class="text-sm font-semibold uppercase tracking-[0.08em] text-slate-700">Categories</p>
                                        <span class="inline-flex items-center rounded-full border border-[#1e73be]/25 bg-[#1e73be]/10 px-2.5 py-1 text-xs font-semibold text-[#1e73be]">
                                            {{ $categoryGroups->count() }} groups
                                        </span>
                                    </div>

                                    <div class="grid gap-3 p-4 sm:grid-cols-2">
                                        @foreach ($categoryGroups as $group)
                                            @php
                                                $parentCategory = $group->firstWhere('parent_id', null);
                                                $childCategories = $group->filter(fn ($category) => $category->parent_id)->values();
                                                $groupTitle = $parentCategory?->name ?: $group->first()->parent?->name ?: $group->first()->name;
                                            @endphp
                                            <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-[0_6px_18px_rgba(15,23,42,0.05)]">
                                                <p class="text-[15px] font-semibold text-slate-900">{{ $groupTitle }}</p>

                                                @if ($childCategories->isNotEmpty())
                                                    <div class="mt-3 flex flex-wrap gap-2">
                                                        @foreach ($childCategories as $child)
                                                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[13px] font-medium text-slate-700">
                                                                {{ $child->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="mt-2 text-[13px] text-slate-500">No child categories.</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>

                    @php
                        $featureSections = [
                            [
                                'title' => 'Interior Details',
                                'items' => ['Equiped Kitchen', 'Gym', 'Laundry', 'Media Room'],
                            ],
                            [
                                'title' => 'Outdoor Details',
                                'items' => ['Back yard', 'Basketball court', 'Front yard', 'Garage Attached', 'Hot Bath', 'Pool'],
                            ],
                            [
                                'title' => 'Utilities',
                                'items' => ['Central Air', 'Electricity', 'Heating', 'Natural Gas', 'Ventilation', 'Water'],
                            ],
                            [
                                'title' => 'Other Features',
                                'items' => [
                                    'Chair Accessible', 'Water', 'Central Air',
                                    'Elevator', 'Hot Bath', 'Heating',
                                    'Basketball court', 'Fireplace', 'Equiped Kitchen',
                                    'Smoke detectors', 'Electricity', 'Garage Attached',
                                    'Natural Gas', 'Gym', 'Back yard',
                                    'Washer and dryer', 'Laundry', 'Front yard',
                                    'Pool', 'Media Room', 'Ventilation',
                                    'WiFi',
                                ],
                            ],
                        ];
                    @endphp

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Features</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5 space-y-5 text-[15px] text-slate-600">
                                @if ($selectedFeatures->isNotEmpty())
                                    @foreach ($groupedFeatures as $featureGroup)
                                        @php
                                            $parentFeature = $featureGroup->firstWhere('parent_id', null) ?? $featureGroup->first()?->parent;
                                            $groupTitle = $parentFeature?->name ?? 'Features';
                                            $groupItems = $featureGroup->filter(function ($feature) use ($parentFeature) {
                                                return !$parentFeature || $feature->id !== $parentFeature->id;
                                            })->values();
                                        @endphp
                                        <div>
                                            <h3 class="text-base font-semibold text-slate-900">{{ $groupTitle }}</h3>
                                            <ul class="mt-3 grid gap-x-6 gap-y-2 sm:grid-cols-2 lg:grid-cols-3">
                                                @if ($groupItems->isNotEmpty())
                                                    @foreach ($groupItems as $feature)
                                                        <li class="inline-flex items-center gap-2.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-[#1e73be]" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.78-9.72a.75.75 0 00-1.06-1.06L9.25 10.69 7.78 9.22a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l4-4z" clip-rule="evenodd" />
                                                            </svg>
                                                            <span>{{ $feature->name }}</span>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li class="inline-flex items-center gap-2.5 text-slate-500">
                                                        No child features selected.
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach ($featureSections as $featureSection)
                                        <div>
                                            <h3 class="text-base font-semibold text-slate-900">{{ $featureSection['title'] }}</h3>
                                            <ul class="mt-3 grid gap-x-6 gap-y-2 sm:grid-cols-2 lg:grid-cols-3">
                                                @foreach ($featureSection['items'] as $feature)
                                                    <li class="inline-flex items-center gap-2.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-[#1e73be]" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.78-9.72a.75.75 0 00-1.06-1.06L9.25 10.69 7.78 9.22a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span>{{ $feature }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </details>
                    </article>

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Video</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5">
                                @if ($videoUrl)
                                    <video controls class="h-[320px] w-full rounded-lg bg-black object-cover">
                                        <source src="{{ $videoUrl }}">
                                    </video>
                                @else
                                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-slate-500">
                                        No video uploaded for this property.
                                    </div>
                                @endif
                            </div>
                        </details>
                    </article>

                    {{-- <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Virtual Tour</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5">
                                <a href="https://matterport.com/discover/space/4xxv6Tde1UJ" target="_blank" rel="noopener" class="group/tour relative block overflow-hidden rounded-lg">
                                    <img src="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1600&q=80" alt="Virtual tour preview" class="h-[320px] w-full object-cover transition duration-300 group-hover/tour:scale-[1.02]">
                                    <span class="pointer-events-none absolute inset-0 bg-slate-900/30"></span>
                                    <span class="pointer-events-none absolute left-1/2 top-[22%] -translate-x-1/2 text-center text-[36px] font-medium leading-tight text-white">West Loop Chicago Condo (Pro2)</span>

                                    <span class="absolute left-1/2 top-1/2 inline-flex h-16 w-16 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-slate-800 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5v14l11-7L8 5z" />
                                        </svg>
                                    </span>

                                    <span class="pointer-events-none absolute bottom-6 left-1/2 -translate-x-1/2 text-center text-white">
                                        <span class="block text-[9px] font-semibold uppercase tracking-[0.2em]">Powered by</span>
                                        <span class="mt-1 block text-[32px] font-semibold leading-none">Matterport</span>
                                    </span>
                                </a>
                            </div>
                        </details>
                    </article> --}}

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Map</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5 overflow-hidden rounded-lg border border-slate-200">
                                <iframe
                                    title="Property location map"
                                    src="{{ $mapSrc }}"
                                    class="h-[340px] w-full"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                ></iframe>
                            </div>
                        </details>
                    </article>

                    {{-- <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Walkscore</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5">
                                <p class="text-[18px] text-slate-600">1244 E Street 56852 Jersey City New Jersey State</p>

                                <div class="mt-4 flex items-center gap-4 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                    <span class="border-b-2 border-[#1e73be] pb-1 text-[#1e73be]">Scores</span>
                                    <span>Nearby</span>
                                </div>

                                <div class="mt-4 space-y-4">
                                    <div class="flex items-center gap-4">
                                        <span class="inline-flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-md border border-[#9fc4f2] bg-[#eaf3ff] text-[#1e73be]">
                                            <span class="text-[8px] font-semibold uppercase leading-none">Walk</span>
                                            <span class="text-[30px] font-bold leading-none">96</span>
                                        </span>
                                        <div>
                                            <p class="text-[20px] font-semibold leading-none text-slate-700">Walker's Paradise</p>
                                            <p class="mt-1 text-[14px] text-slate-500">Daily errands do not require a car</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <span class="inline-flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-md border border-[#9fc4f2] bg-[#eaf3ff] text-[#1e73be]">
                                            <span class="text-[8px] font-semibold uppercase leading-none">Transit</span>
                                            <span class="text-[30px] font-bold leading-none">83</span>
                                        </span>
                                        <div>
                                            <p class="text-[20px] font-semibold leading-none text-slate-700">Excellent Transit</p>
                                            <p class="mt-1 text-[14px] text-slate-500">Transit is convenient for most trips</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <span class="inline-flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-md border border-[#9fc4f2] bg-[#eaf3ff] text-[#1e73be]">
                                            <span class="text-[8px] font-semibold uppercase leading-none">Bike</span>
                                            <span class="text-[30px] font-bold leading-none">58</span>
                                        </span>
                                        <div>
                                            <p class="text-[20px] font-semibold leading-none text-slate-700">Bikeable</p>
                                            <p class="mt-1 text-[14px] text-slate-500">Some bike infrastructure</p>
                                        </div>
                                    </div>
                                </div>

                                <a href="https://www.walkscore.com/" target="_blank" rel="noopener" class="mt-4 inline-block text-[15px] font-medium text-[#1e73be] hover:underline">View map of nearby restaurants, grocery stores, and more.</a>

                                <div class="mt-4 relative">
                                    <input type="text" placeholder="Get scores for your address" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 pr-10 text-[16px] text-slate-700 outline-none focus:border-[#1e73be]">
                                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l3.39 3.38a1 1 0 01-1.42 1.42l-3.38-3.39zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </details>
                    </article> --}}

                    {{-- <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Calculator</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5 grid gap-6 lg:grid-cols-[270px_minmax(0,1fr)]">
                                <div>
                                    <div class="mx-auto grid h-[150px] w-[150px] place-items-center rounded-full" style="background: conic-gradient(#1f73dd 0 75%, #28b8e8 75% 95%, #ef6b6b 95% 98%, #7d3dd8 98% 100%);">
                                        <div class="grid h-[104px] w-[104px] place-items-center rounded-full bg-white text-center">
                                            <span class="block text-[34px] font-bold leading-none text-slate-800">$461.45</span>
                                            <span class="mt-1 block text-[14px] text-slate-500">per month</span>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex flex-wrap gap-x-4 gap-y-2 text-[14px] text-slate-600">
                                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 bg-[#1f73dd]"></span>Principal and Interest</span>
                                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 bg-[#28b8e8]"></span>Property Tax</span>
                                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 bg-[#ef6b6b]"></span>HOA fee</span>
                                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 bg-[#7d3dd8]"></span>Private Mortgage Insurance</span>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <p class="text-[16px] font-semibold text-slate-900">Principal and Interest</p>
                                        <p class="mt-1 text-[20px] text-slate-600">$ 348.95</p>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-[15px] font-semibold text-slate-800">Property Tax</label>
                                        <div class="relative">
                                            <input type="text" value="112.5" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 pr-9 text-[15px] text-slate-700 outline-none focus:border-[#1e73be]">
                                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-[15px] font-semibold text-slate-800">Private Mortgage Insurance (PMI)</label>
                                        <div class="grid grid-cols-[1fr_56px_72px] gap-2">
                                            <input type="text" value="0" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[15px] text-slate-700 outline-none focus:border-[#1e73be]">
                                            <div class="grid place-items-center rounded border border-slate-300 bg-[#f7f7f8] text-[15px] text-slate-500">%</div>
                                            <div class="grid place-items-center rounded border border-slate-300 bg-[#f7f7f8] text-[15px] text-slate-500">0 $</div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-[15px] font-semibold text-slate-800">Monthly HOA Fees</label>
                                        <div class="relative">
                                            <input type="text" value="0" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 pr-9 text-[15px] text-slate-700 outline-none focus:border-[#1e73be]">
                                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-[15px] font-semibold text-slate-800">Total Amount</label>
                                    <div class="relative">
                                        <input type="text" value="90000" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 pr-9 text-[15px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-[15px] font-semibold text-slate-800">Loan Terms (Years)</label>
                                    <input type="text" value="30" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[15px] text-slate-700 outline-none focus:border-[#1e73be]">
                                </div>

                                <div>
                                    <label class="mb-1 block text-[15px] font-semibold text-slate-800">Down Payment</label>
                                    <div class="grid grid-cols-[1fr_56px_56px] gap-2">
                                        <input type="text" value="18000" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[15px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <div class="grid place-items-center rounded border border-slate-300 bg-[#f7f7f8] text-[15px] text-slate-500">$</div>
                                        <div class="grid place-items-center rounded border border-slate-300 bg-[#f7f7f8] text-[15px] text-slate-500">20 %</div>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-[15px] font-semibold text-slate-800">Interest</label>
                                    <div class="relative">
                                        <input type="text" value="4.125" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 pr-9 text-[15px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">%</span>
                                    </div>
                                </div>
                            </div>
                        </details>
                    </article> --}}

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Floor Plans</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5">
                                <div class="rounded border border-slate-200 bg-[#f8f9fb] px-3 py-2 text-[14px] text-slate-600 sm:grid sm:grid-cols-[minmax(0,1fr)_auto_auto_auto_auto] sm:items-center sm:gap-6">
                                    <span class="font-semibold text-slate-800">Floor Plan</span>
                                    <span><span class="font-semibold text-slate-700">Price:</span> {{ $priceText }}</span>
                                    <span><span class="font-semibold text-slate-700">Baths:</span> {{ $property->bathrooms ?: 'N/A' }}</span>
                                    <span><span class="font-semibold text-slate-700">Rooms:</span> {{ $property->rooms ?: 'N/A' }}</span>
                                    <span><span class="font-semibold text-slate-700">Size:</span> {{ $property->size ?: 'N/A' }}</span>
                                </div>

                                <div class="px-1 pb-1 pt-4">
                                    @if ($floorPlanUrl)
                                        <div class="mx-auto w-full max-w-[840px] overflow-hidden rounded-lg border border-slate-200 bg-white" style="height: 980px;">
                                            <iframe src="{{ $floorPlanUrl }}" class="h-full w-full" title="Floor plan"></iframe>
                                        </div>
                                        <p class="mt-4 text-[15px] leading-7 text-slate-600">View or download the uploaded floor plan above.</p>
                                    @else
                                        <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-slate-500">
                                            No floor plan uploaded for this property.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </details>
                    </article>

                    {{-- <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Statistics</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5 rounded-lg border border-slate-200 bg-[#f9fafb] p-4">
                                <div class="mb-3 flex items-center justify-center gap-2 text-[13px] text-slate-600">
                                    <span class="inline-block h-3 w-8 rounded bg-slate-200"></span>
                                    <span>Property Views</span>
                                </div>

                                <svg viewBox="0 0 760 360" class="w-full" role="img" aria-label="Property views statistics chart">
                                    <rect x="58" y="20" width="672" height="260" fill="#ffffff" stroke="#d5dbe4" />

                                    <g stroke="#e5e7eb" stroke-width="1">
                                        <line x1="58" y1="20" x2="730" y2="20" />
                                        <line x1="58" y1="52" x2="730" y2="52" />
                                        <line x1="58" y1="84" x2="730" y2="84" />
                                        <line x1="58" y1="116" x2="730" y2="116" />
                                        <line x1="58" y1="148" x2="730" y2="148" />
                                        <line x1="58" y1="180" x2="730" y2="180" />
                                        <line x1="58" y1="212" x2="730" y2="212" />
                                        <line x1="58" y1="244" x2="730" y2="244" />
                                        <line x1="58" y1="280" x2="730" y2="280" />

                                        <line x1="100" y1="20" x2="100" y2="280" />
                                        <line x1="148" y1="20" x2="148" y2="280" />
                                        <line x1="196" y1="20" x2="196" y2="280" />
                                        <line x1="244" y1="20" x2="244" y2="280" />
                                        <line x1="292" y1="20" x2="292" y2="280" />
                                        <line x1="340" y1="20" x2="340" y2="280" />
                                        <line x1="388" y1="20" x2="388" y2="280" />
                                        <line x1="436" y1="20" x2="436" y2="280" />
                                        <line x1="484" y1="20" x2="484" y2="280" />
                                        <line x1="532" y1="20" x2="532" y2="280" />
                                        <line x1="580" y1="20" x2="580" y2="280" />
                                        <line x1="628" y1="20" x2="628" y2="280" />
                                        <line x1="676" y1="20" x2="676" y2="280" />
                                    </g>

                                    <g fill="#dcdcdc" opacity="0.6">
                                        <rect x="154" y="20" width="36" height="260" />
                                        <rect x="596" y="20" width="36" height="260" />
                                    </g>

                                    <g fill="#6b7280" font-size="12" font-weight="600">
                                        <text x="40" y="24" text-anchor="end">6.0</text>
                                        <text x="40" y="56" text-anchor="end">5.8</text>
                                        <text x="40" y="88" text-anchor="end">5.6</text>
                                        <text x="40" y="120" text-anchor="end">5.4</text>
                                        <text x="40" y="152" text-anchor="end">5.2</text>
                                        <text x="40" y="184" text-anchor="end">5.0</text>
                                        <text x="40" y="216" text-anchor="end">4.8</text>
                                        <text x="40" y="248" text-anchor="end">4.6</text>
                                        <text x="40" y="280" text-anchor="end">4.4</text>
                                    </g>

                                    <g fill="#6b7280" font-size="11">
                                        <text transform="translate(68,322) rotate(-35)">June 3, 2026</text>
                                        <text transform="translate(118,322) rotate(-35)">June 4, 2026</text>
                                        <text transform="translate(168,322) rotate(-35)">June 5, 2026</text>
                                        <text transform="translate(218,322) rotate(-35)">June 6, 2026</text>
                                        <text transform="translate(268,322) rotate(-35)">June 7, 2026</text>
                                        <text transform="translate(318,322) rotate(-35)">June 8, 2026</text>
                                        <text transform="translate(368,322) rotate(-35)">June 9, 2026</text>
                                        <text transform="translate(418,322) rotate(-35)">June 10, 2026</text>
                                        <text transform="translate(468,322) rotate(-35)">July 1, 2026</text>
                                        <text transform="translate(518,322) rotate(-35)">July 2, 2026</text>
                                        <text transform="translate(568,322) rotate(-35)">July 3, 2026</text>
                                        <text transform="translate(618,322) rotate(-35)">July 4, 2026</text>
                                        <text transform="translate(668,322) rotate(-35)">July 5, 2026</text>
                                    </g>
                                </svg>
                            </div>
                        </details>
                    </article> --}}

                    {{-- <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <details class="group" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 [&::-webkit-details-marker]:hidden">
                                <h2 class="section-title">Schedule a tour</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1e73be] transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </summary>

                            <div class="mt-5 grid gap-4 md:grid-cols-[48%_52%]">
                                <div>
                                    <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80" alt="Schedule a tour preview" class="h-full min-h-[360px] w-full rounded object-cover">
                                </div>

                                <div>
                                    <div class="grid grid-cols-[28px_1fr_1fr_1fr_1fr_28px] gap-2">
                                        <button type="button" class="inline-flex h-14 items-center justify-center rounded border border-slate-200 text-[#1e73be]">&larr;</button>
                                        <button type="button" class="rounded border border-slate-200 px-2 py-1 text-center text-sm text-slate-700">
                                            <span class="block">Tue</span>
                                            <span class="block text-lg font-semibold">14</span>
                                            <span class="block">Jul</span>
                                        </button>
                                        <button type="button" class="rounded border border-slate-200 px-2 py-1 text-center text-sm text-slate-700">
                                            <span class="block">Wed</span>
                                            <span class="block text-lg font-semibold">15</span>
                                            <span class="block">Jul</span>
                                        </button>
                                        <button type="button" class="rounded border border-slate-200 px-2 py-1 text-center text-sm text-slate-700">
                                            <span class="block">Mon</span>
                                            <span class="block text-lg font-semibold">06</span>
                                            <span class="block">Jul</span>
                                        </button>
                                        <button type="button" class="rounded border border-[#1e73be] bg-[#f0f6ff] px-2 py-1 text-center text-sm text-[#1e73be]">
                                            <span class="block">Tue</span>
                                            <span class="block text-lg font-semibold">07</span>
                                            <span class="block">Jul</span>
                                        </button>
                                        <button type="button" class="inline-flex h-14 items-center justify-center rounded border border-slate-200 text-[#1e73be]">&rarr;</button>
                                    </div>

                                    <form class="mt-4 space-y-3">
                                        <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                            <option>Please select the time</option>
                                            <option>10:00 AM</option>
                                            <option>12:00 PM</option>
                                            <option>02:00 PM</option>
                                        </select>

                                        <div class="grid grid-cols-2 gap-2">
                                            <button type="button" class="inline-flex items-center justify-center gap-1.5 rounded border border-[#1e73be] bg-[#f0f6ff] px-3 py-2 text-sm font-medium text-[#1e73be]">
                                                <span>In Person</span>
                                            </button>
                                            <button type="button" class="inline-flex items-center justify-center gap-1.5 rounded border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600">
                                                <span>Video Chat</span>
                                            </button>
                                        </div>

                                        <input type="text" placeholder="Name" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                        <input type="email" placeholder="Email" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                        <input type="tel" placeholder="Phone" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">

                                        <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                            <option>What are you looking to do?</option>
                                            <option>Buy</option>
                                            <option>Rent</option>
                                            <option>Invest</option>
                                        </select>

                                        <textarea rows="5" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">I would like to schedule a tour for [ Family House In Hudson ].</textarea>

                                        <button type="submit" class="w-full rounded bg-[#1279eb] px-4 py-2.5 text-base font-bold text-white hover:bg-[#0f6fcb]">Send</button>
                                    </form>
                                </div>
                            </div>
                        </details>
                    </article> --}}

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <h2 class="section-title">Property Reviews</h2>
                        <p class="mt-3 text-[15px] font-medium text-slate-700">No reviews found.</p>
                        <div class="mt-4 rounded bg-[#f5f6f8] px-3 py-2 text-[14px] text-slate-700">
                            You need to <a href="#" class="font-semibold text-[#1e73be] hover:underline">login</a> in order to post a review
                        </div>
                    </article>

                    <section>
                        <h2 class="section-title">Similar Listings</h2>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <article class="property-listing-card overflow-hidden rounded border border-slate-200 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04)]">
                                <div class="relative">
                                    <a href="#" class="block">
                                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80" alt="Luxury House in Greenville" class="h-52 w-full object-cover">
                                    </a>
                                    <div class="absolute left-3 top-3 rounded bg-emerald-500 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">Featured</div>
                                    <div class="absolute right-3 top-3 flex gap-1">
                                        <span class="rounded bg-[#1e73be] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">Sales</span>
                                        <span class="rounded bg-[#2f7ecc] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">Active</span>
                                    </div>
                                    <div class="absolute bottom-3 left-3 text-xs text-white drop-shadow-sm">Greenville, Jersey City</div>
                                    <div class="absolute bottom-3 right-3 text-xs text-white">7</div>
                                </div>

                                <div class="p-4">
                                    <h3 class="property-card-title">Luxury House in Greenville</h3>
                                    <p class="property-card-price mt-2">$860,000</p>
                                    <p class="property-card-copy mt-3">This property is mostly wooded and sits high on a hilltop overlooking the Mohawk River Val ...</p>

                                    <div class="property-card-meta mt-4 flex items-center gap-4 border-b border-slate-200 pb-3">
                                        <span>5</span>
                                        <span>6</span>
                                        <span>190 ft<sup>2</sup></span>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <img src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=120&q=80" alt="John Collins" class="h-7 w-7 rounded-full object-cover">
                                            <span class="property-card-agent">John Collins</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-slate-400">
                                            <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Share">+</button>
                                            <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Favorite">♡</button>
                                            <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Add">+</button>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <article class="property-listing-card overflow-hidden rounded border border-slate-200 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04)]">
                                <div class="relative">
                                    <a href="#" class="block">
                                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80" alt="Modern Condo for Sale" class="h-52 w-full object-cover">
                                    </a>
                                    <div class="absolute left-3 top-3 rounded bg-emerald-500 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">Featured</div>
                                    <div class="absolute right-3 top-3 flex gap-1">
                                        <span class="rounded bg-[#1e73be] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">Sales</span>
                                        <span class="rounded bg-[#2f7ecc] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">Open House</span>
                                    </div>
                                    <div class="absolute bottom-3 left-3 text-xs text-white drop-shadow-sm">Manhattan, New York</div>
                                    <div class="absolute bottom-3 right-3 text-xs text-white">7</div>
                                </div>

                                <div class="p-4">
                                    <h3 class="property-card-title">Modern Condo for Sale</h3>
                                    <p class="property-card-price mt-2">$150,000</p>
                                    <p class="property-card-copy mt-3">Beautiful, updated, ground level Co-op apartment in the desirable Bay Terrace neighborhood ...</p>

                                    <div class="property-card-meta mt-4 flex items-center gap-4 border-b border-slate-200 pb-3">
                                        <span>2</span>
                                        <span>2</span>
                                        <span>200 ft<sup>2</sup></span>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80" alt="Michael Sutther" class="h-7 w-7 rounded-full object-cover">
                                            <span class="property-card-agent">Michael Sutther</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-slate-400">
                                            <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Share">+</button>
                                            <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Favorite">♡</button>
                                            <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Add">+</button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>
                </div>

                <aside class="sticky self-start" style="top:3.5rem;">
                    <div class="rounded-xl bg-white shadow-[0_10px_30px_rgba(15,23,42,0.08)]">
                        <div class="grid grid-cols-2 border-b border-slate-200 text-center text-[15px] font-semibold" data-sidebar-tabs>
                            <button type="button" class="border-b-2 border-[#1e73be] px-4 py-3 text-[#1f2a44]" data-sidebar-tab="request" aria-pressed="true">Request Info</button>
                            <button type="button" class="px-4 py-3 text-slate-500" data-sidebar-tab="tour" aria-pressed="false">Schedule a tour</button>
                        </div>

                        <div class="p-4 sm:p-5" data-sidebar-panel="request">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80" alt="Lara Black" class="h-14 w-14 rounded-full object-cover">
                                <div>
                                    <p class="text-lg font-bold text-[#1f2a44]">Lara Black</p>
                                    <p class="text-sm text-slate-500">Member ID: 987654321</p>
                                </div>
                            </div>

                            <div class="mt-4 rounded bg-[#67c47c] px-3 py-1.5 text-sm font-semibold text-white">Schedule a showing?</div>

                            <form class="mt-4 space-y-3">
                                <input type="text" placeholder="Name" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                <input type="email" placeholder="Email" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                <input type="tel" placeholder="Phone" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                    <option>What are you looking to do?</option>
                                    <option>Buy</option>
                                    <option>Rent</option>
                                    <option>Invest</option>
                                </select>
                                <textarea rows="6" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">Hello,
I'm interested in [ Suburb Home for Sale ]</textarea>

                                <button type="submit" class="w-full rounded bg-[#1279eb] px-4 py-2.5 text-base font-bold text-white hover:bg-[#0f6fcb]">Send</button>
                                <button type="button" class="w-full rounded border border-[#1279eb] px-4 py-2.5 text-base font-bold text-[#1279eb] hover:bg-[#f3f8ff]">Call</button>
                                <button type="button" class="w-full rounded border border-[#1279eb] px-4 py-2.5 text-base font-bold text-[#1279eb] hover:bg-[#f3f8ff]">WhatsApp</button>
                            </form>
                        </div>

                        <div class="hidden p-4 sm:p-5" data-sidebar-panel="tour">
                            <h3 class="text-[30px] font-semibold leading-tight tracking-[-0.02em] text-[#1f2a44]">Schedule a tour</h3>

                            <div class="mt-4 grid grid-cols-[1fr_1fr_1fr_1fr] gap-2">
                                <button type="button" class="rounded border border-slate-200 px-2 py-1 text-center text-sm text-slate-700">
                                    <span class="block">Mon</span>
                                    <span class="block text-lg font-semibold">06</span>
                                    <span class="block">Jul</span>
                                </button>
                                <button type="button" class="rounded border border-[#1e73be] bg-[#f0f6ff] px-2 py-1 text-center text-sm text-[#1e73be]">
                                    <span class="block">Tue</span>
                                    <span class="block text-lg font-semibold">07</span>
                                    <span class="block">Jul</span>
                                </button>
                                <button type="button" class="rounded border border-slate-200 px-2 py-1 text-center text-sm text-slate-700">
                                    <span class="block">Wed</span>
                                    <span class="block text-lg font-semibold">08</span>
                                    <span class="block">Jul</span>
                                </button>
                                <button type="button" class="rounded border border-slate-200 px-2 py-1 text-center text-sm text-slate-700">
                                    <span class="block">Thu</span>
                                    <span class="block text-lg font-semibold">09</span>
                                    <span class="block">Jul</span>
                                </button>
                            </div>

                            <form class="mt-4 space-y-3">
                                <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                    <option>Please select the time</option>
                                    <option>10:00 AM</option>
                                    <option>12:00 PM</option>
                                    <option>02:00 PM</option>
                                </select>

                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" class="inline-flex items-center justify-center gap-1.5 rounded border border-[#1e73be] bg-[#f0f6ff] px-3 py-2 text-sm font-medium text-[#1e73be]">In Person</button>
                                    <button type="button" class="inline-flex items-center justify-center gap-1.5 rounded border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600">Video Chat</button>
                                </div>

                                <p class="pt-2 text-[18px] font-semibold text-[#1f2a44]">Your information</p>

                                <input type="text" placeholder="Name" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                <input type="email" placeholder="Email" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                <input type="tel" placeholder="Phone" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">

                                <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">
                                    <option>What are you looking to do?</option>
                                    <option>Buy</option>
                                    <option>Rent</option>
                                    <option>Invest</option>
                                </select>

                                <textarea rows="5" class="w-full rounded border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1e73be]">I would like to schedule a tour for [ Family House In Hudson ].</textarea>

                                <button type="submit" class="w-full rounded bg-[#1279eb] px-4 py-2.5 text-base font-bold text-white hover:bg-[#0f6fcb]">Send</button>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
