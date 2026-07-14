@php
    $listingProperties = $properties ?? collect();
    $filters = $filters ?? [];
    $filterOptions = $filterOptions ?? [];
    $typeOptions = collect($filterOptions['types'] ?? []);
    $categoryOptions = collect($filterOptions['categories'] ?? []);
    $stateOptions = collect($filterOptions['states'] ?? []);
    $cityOptions = collect($filterOptions['cities'] ?? []);
    $areaOptions = collect($filterOptions['areas'] ?? []);

    $sortOptions = [
        'newest' => 'Newest',
        'price_asc' => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'featured' => 'Featured',
    ];

    $buildQuery = function (array $overrides = []) use ($filters) {
        return collect(array_merge($filters, $overrides))
            ->filter(function ($value) {
                return $value !== null && $value !== '' && $value !== 0;
            })
            ->all();
    };
@endphp

<section class="bg-[#f3f5f7] py-8 sm:py-10">
    <div class="section-shell">
        <div class="properties-listing-layout">
            <aside class="space-y-5 properties-sidebar">
                <form method="GET" action="{{ route('properties') }}" class="rounded bg-[#ececec] p-5">
                    <h3 class="text-[22px] font-semibold tracking-[-0.02em] text-[#1f2a44]">Quick Filters</h3>
                    <div class="mt-4 space-y-3">
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search title, city, state" class="w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[#1e73be] focus:outline-none" />
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" min="0" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min price" class="w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[#1e73be] focus:outline-none" />
                            <input type="number" min="0" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max price" class="w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[#1e73be] focus:outline-none" />
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" min="0" name="beds" value="{{ $filters['beds'] ?? '' }}" placeholder="Min beds" class="w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[#1e73be] focus:outline-none" />
                            <input type="number" min="0" name="baths" value="{{ $filters['baths'] ?? '' }}" placeholder="Min baths" class="w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[#1e73be] focus:outline-none" />
                        </div>
                        <button type="submit" class="w-full rounded bg-[#1e73be] px-3 py-2 text-sm font-semibold text-white hover:bg-[#155a95]">Apply Filters</button>
                        <a href="{{ route('properties') }}" class="block w-full rounded border border-slate-300 bg-white px-3 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-100">Reset</a>
                    </div>
                </form>

                <details class="group rounded bg-[#ececec] p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left [&::-webkit-details-marker]:hidden">
                        <h3 class="text-[22px] font-semibold tracking-[-0.02em] text-[#1f2a44]">List by Categories</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-slate-500 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <ul class="mt-4 divide-y divide-slate-300/70 text-sm">
                        @foreach ($categoryOptions as $item)
                            @php $isActive = (string) ($filters['category'] ?? '') === (string) $item->id; @endphp
                            <li>
                                <a href="{{ route('properties', $buildQuery(['category' => $item->id])) }}" class="flex items-center justify-between py-3 {{ $isActive ? 'font-semibold text-[#1e73be]' : 'text-slate-700' }}">
                                    <span>{{ $item->name }}</span>
                                    <span class="text-slate-500">({{ $item->active_properties_count }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>

                <details class="group rounded bg-[#ececec] p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left [&::-webkit-details-marker]:hidden">
                        <h3 class="text-[22px] font-semibold tracking-[-0.02em] text-[#1f2a44]">List by Types</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-slate-500 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <ul class="mt-4 divide-y divide-slate-300/70 text-sm">
                        @foreach ($typeOptions as $item)
                            @php $isActive = (string) ($filters['type'] ?? '') === (string) $item->value; @endphp
                            <li>
                                <a href="{{ route('properties', $buildQuery(['type' => $item->value])) }}" class="flex items-center justify-between py-3 {{ $isActive ? 'font-semibold text-[#1e73be]' : 'text-slate-700' }}">
                                    <span>{{ \Illuminate\Support\Str::title($item->label) }}</span>
                                    <span class="text-slate-500">({{ $item->aggregate }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>

                <details class="group rounded bg-[#ececec] p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left [&::-webkit-details-marker]:hidden">
                        <h3 class="text-[22px] font-semibold tracking-[-0.02em] text-[#1f2a44]">List by Cities</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-slate-500 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <ul class="mt-4 divide-y divide-slate-300/70 text-sm">
                        @foreach ($cityOptions as $item)
                            @php $isActive = (string) ($filters['city'] ?? '') === (string) $item->id; @endphp
                            <li>
                                <a href="{{ route('properties', $buildQuery(['city' => $item->id])) }}" class="flex items-center justify-between py-3 {{ $isActive ? 'font-semibold text-[#1e73be]' : 'text-slate-700' }}">
                                    <span>{{ $item->name }}</span>
                                    <span class="text-slate-500">({{ $item->aggregate }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>

                <details class="group rounded bg-[#ececec] p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left [&::-webkit-details-marker]:hidden">
                        <h3 class="text-[22px] font-semibold tracking-[-0.02em] text-[#1f2a44]">List by Areas</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-slate-500 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <ul class="mt-4 divide-y divide-slate-300/70 text-sm">
                        @foreach ($areaOptions as $item)
                            @php $isActive = (string) ($filters['area'] ?? '') === (string) $item->name; @endphp
                            <li>
                                <a href="{{ route('properties', $buildQuery(['area' => $item->name])) }}" class="flex items-center justify-between py-3 {{ $isActive ? 'font-semibold text-[#1e73be]' : 'text-slate-700' }}">
                                    <span>{{ $item->name }}</span>
                                    <span class="text-slate-500">({{ $item->aggregate }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            </aside>

            <div>
                {{-- ===== FILTER TOOLBAR ===== --}}
                <div data-properties-toolbar class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md">

                    {{-- Top bar: title + view toggle --}}
                    <div class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-[#1e73be]/8 via-transparent to-transparent px-5 py-3">
                        <div class="flex items-center gap-2 text-[#1e73be]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                            </svg>
                            <span class="text-sm font-semibold tracking-wide text-slate-700">Filter Properties</span>
                        </div>
                        <div id="properties-view-controls" class="flex items-center gap-1.5">
                            <span class="mr-1 text-xs text-slate-400">View:</span>
                            <button type="button" data-properties-view="grid" aria-label="Grid view"
                                class="properties-view-btn is-active inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-[#1e73be] transition hover:border-[#1e73be] hover:shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 3h5v5H3V3zm0 9h5v5H3v-5zm9-9h5v5h-5V3zm0 9h5v5h-5v-5z" />
                                </svg>
                            </button>
                            <button type="button" data-properties-view="list" aria-label="List view"
                                class="properties-view-btn inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 transition hover:border-[#1e73be] hover:text-[#1e73be] hover:shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 5h14a1 1 0 100-2H3a1 1 0 000 2zm14 4H3a1 1 0 100 2h14a1 1 0 100-2zm0 6H3a1 1 0 100 2h14a1 1 0 100-2z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Filter row --}}
                    <form method="GET" action="{{ route('properties') }}">
                        <input type="hidden" name="q" value="{{ $filters['q'] ?? '' }}" />
                        <input type="hidden" name="min_price" value="{{ $filters['min_price'] ?? '' }}" />
                        <input type="hidden" name="max_price" value="{{ $filters['max_price'] ?? '' }}" />
                        <input type="hidden" name="beds" value="{{ $filters['beds'] ?? '' }}" />
                        <input type="hidden" name="baths" value="{{ $filters['baths'] ?? '' }}" />

                        @php
                            $filterClasses = 'h-10 w-full appearance-none rounded-lg border border-slate-200 bg-slate-50 px-3 pr-9 text-sm text-slate-700 transition focus:border-[#1e73be] focus:bg-white focus:ring-2 focus:ring-[#1e73be]/15 focus:outline-none';
                            $chevron = '<span class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center text-slate-400"><svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-3.5 w-3.5\' viewBox=\'0 0 20 20\' fill=\'currentColor\'><path d=\'M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 011.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z\'/></svg></span>';
                        @endphp

                        {{-- Dropdowns grid --}}
                        <div class="grid grid-cols-2 gap-3 px-4 pt-4 sm:grid-cols-3 lg:grid-cols-6">

                            {{-- Type --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Type</label>
                                <div class="relative">
                                    <select name="type" class="{{ $filterClasses }}">
                                        <option value="">All Types</option>
                                        @foreach ($typeOptions as $item)
                                            <option value="{{ $item->value }}" {{ (string) ($filters['type'] ?? '') === (string) $item->value ? 'selected' : '' }}>{{ \Illuminate\Support\Str::title($item->label) }}</option>
                                        @endforeach
                                    </select>
                                    {!! $chevron !!}
                                </div>
                            </div>

                            {{-- Category --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Category</label>
                                <div class="relative">
                                    <select name="category" class="{{ $filterClasses }}">
                                        <option value="">All Categories</option>
                                        @foreach ($categoryOptions as $item)
                                            <option value="{{ $item->id }}" {{ (string) ($filters['category'] ?? '') === (string) $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $chevron !!}
                                </div>
                            </div>

                            {{-- State --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">State</label>
                                <div class="relative">
                                    <select name="state" class="{{ $filterClasses }}">
                                        <option value="">All States</option>
                                        @foreach ($stateOptions as $item)
                                            <option value="{{ $item->id }}" {{ (string) ($filters['state'] ?? '') === (string) $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $chevron !!}
                                </div>
                            </div>

                            {{-- City --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">City</label>
                                <div class="relative">
                                    <select name="city" class="{{ $filterClasses }}">
                                        <option value="">All Cities</option>
                                        @foreach ($cityOptions as $item)
                                            <option value="{{ $item->id }}" {{ (string) ($filters['city'] ?? '') === (string) $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $chevron !!}
                                </div>
                            </div>

                            {{-- Area --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Area</label>
                                <div class="relative">
                                    <select name="area" class="{{ $filterClasses }}">
                                        <option value="">All Areas</option>
                                        @foreach ($areaOptions as $item)
                                            <option value="{{ $item->name }}" {{ (string) ($filters['area'] ?? '') === (string) $item->name ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $chevron !!}
                                </div>
                            </div>

                            {{-- Sort --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Sort By</label>
                                <div class="relative">
                                    <select name="sort" class="{{ $filterClasses }}">
                                        @foreach ($sortOptions as $value => $label)
                                            <option value="{{ $value }}" {{ (string) ($filters['sort'] ?? 'newest') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    {!! $chevron !!}
                                </div>
                            </div>
                        </div>

                        {{-- Apply button row --}}
                        <div class="mt-1 flex items-center justify-between border-t border-slate-100 px-4 py-3">
                            <button type="submit"
                                style="background: linear-gradient(to right, #1e73be, #1a65a8);"
                                class="inline-flex items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-semibold text-white shadow transition active:scale-95"
                                onmouseover="this.style.background='linear-gradient(to right,#155a95,#124d82)'"
                                onmouseout="this.style.background='linear-gradient(to right,#1e73be,#1a65a8)'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                                </svg>
                                Apply Filters
                            </button>
                            @if(collect($filters)->filter()->isNotEmpty())
                                <a href="{{ route('properties') }}" class="text-xs text-slate-400 transition hover:text-red-500">✕ Clear all</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="properties-cards-grid" data-properties-cards>
                @forelse ($listingProperties as $property)
                    @php
                        $primaryImage = $property->images->firstWhere('is_primary', true) ?? $property->images->first();
                        $imageUrl = $primaryImage
                            ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path)
                            : 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80';
                        $slug = $property->slug ?: $property->id;
                        $location = trim(implode(', ', array_filter([
                            $property->locality,
                            $property->city?->name,
                            $property->state?->name,
                            $property->country?->name,
                        ]))) ?: 'Location not set';
                        $priceText = is_numeric($property->price) ? format_price($property->price) : ($property->price ?: ($property->price_info ?: 'Contact for price'));
                        $beds = $property->bedrooms ?? 0;
                        $baths = $property->bathrooms ?? 0;
                        $size = $property->size ? $property->size . ' ft²' : 'N/A';
                        $mediaCount = $property->images->count();
                        $badgeOne = \Illuminate\Support\Str::title($property->property_purpose ?: 'property');
                        $badgeTwo = \Illuminate\Support\Str::title($property->status ?: 'active');
                        $agentName = $property->builder_developer ?: ($property->categories->first()?->name ?? 'Lux Estate');
                        $agentAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($agentName) . '&background=1e73be&color=ffffff&size=120';
                    @endphp
                    <article class="property-listing-card overflow-hidden rounded border border-slate-200 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04)]">
                        <div class="relative property-listing-media">
                            <a href="{{ route('property-detail', $slug) }}" class="block">
                                <img src="{{ $imageUrl }}" alt="{{ $property->title }}" class="h-56 w-full object-cover">
                            </a>
                            @if ($property->featured)
                                <div class="absolute left-3 top-3 rounded bg-emerald-500 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">Featured</div>
                            @endif
                            <div class="absolute right-3 top-3 flex gap-1">
                                <span class="rounded bg-[#1e73be] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">{{ $badgeOne }}</span>
                                <span class="rounded bg-[#2f7ecc] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">{{ $badgeTwo }}</span>
                            </div>
                            <div class="absolute bottom-3 left-3 flex items-center gap-1.5 text-xs text-white drop-shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 8.05a7 7 0 119.9 9.9L10 22.9l-4.95-4.95a7 7 0 010-9.9zm7.07 2.83a2.5 2.5 0 11-3.54 3.54 2.5 2.5 0 013.54-3.54z" clip-rule="evenodd" transform="translate(0 -4)" />
                                </svg>
                                <span>{{ $location }}</span>
                            </div>
                            <div class="absolute bottom-3 right-3 flex items-center gap-2 text-xs text-white">
                                <span class="inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6a2 2 0 012-2h7a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm13 3h1a2 2 0 012 2v7a2 2 0 01-2 2h-1v-2h1v-7h-1V9z"/></svg>
                                    {{ $mediaCount }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 property-listing-content">
                            <h3 class="property-card-title"><a href="{{ route('property-detail', $slug) }}" class="hover:text-[#1e73be]">{{ $property->title }}</a></h3>
                            <p class="property-card-price mt-1">{{ $priceText }}</p>
                            <p class="property-card-copy mt-3">{{ \Illuminate\Support\Str::limit($property->description ?: 'No description available.', 140) }}</p>

                            <div class="property-card-meta mt-4 flex items-center gap-4 border-b border-slate-200 pb-3">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 7a3 3 0 013-3h10a3 3 0 013 3v9h1a1 1 0 110 2H3a1 1 0 110-2h1V7zm2 9h12V7a1 1 0 00-1-1H7a1 1 0 00-1 1v9z"/></svg>
                                    {{ $beds }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7 4a1 1 0 011 1v1h8V5a1 1 0 112 0v1h1a2 2 0 012 2v9a3 3 0 01-3 3H6a3 3 0 01-3-3V8a2 2 0 012-2h1V5a1 1 0 011-1zm12 6H5v7a1 1 0 001 1h12a1 1 0 001-1v-7z"/></svg>
                                    {{ $baths }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm13 1a1 1 0 10-2 0v6a1 1 0 001 1h5a1 1 0 100-2h-4v-5z"/></svg>
                                    {{ $size }}
                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $agentAvatar }}" alt="{{ $agentName }}" class="h-7 w-7 rounded-full object-cover">
                                    <span class="property-card-agent">{{ $agentName }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-400">
                                    <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Share">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M18 8a3 3 0 10-2.82-4H15a3 3 0 00.18 1L8.91 8.13a3 3 0 100 3.74l6.27 3.13A3 3 0 1016 14a3 3 0 00-.18 1l-6.27-3.13a3 3 0 000-1.74l6.27-3.13A3 3 0 0018 8z"/></svg>
                                    </button>
                                    <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Favorite">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.1 21.35l-1.1-1C5.14 15.24 2 12.39 2 8.99 2 6.14 4.24 4 7.1 4c1.54 0 3.02.72 3.9 1.87A5.07 5.07 0 0114.9 4C17.76 4 20 6.14 20 8.99c0 3.4-3.14 6.25-8.9 11.36l-1 1z"/></svg>
                                    </button>
                                    <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Add">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 11h-6V5a1 1 0 10-2 0v6H5a1 1 0 100 2h6v6a1 1 0 102 0v-6h6a1 1 0 100-2z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                        No properties found.
                    </div>
                @endforelse

                </div>

                @if ($listingProperties instanceof \Illuminate\Contracts\Pagination\Paginator || $listingProperties instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                    <div class="mt-6 flex justify-center">
                        {{ $listingProperties->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

