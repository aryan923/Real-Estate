<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'RealEstate') }} | Home</title>

        @if(!empty($settings['site_fav']))
            <link rel="icon" href="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($settings['site_fav']) }}">
        @else
            <link rel="icon" href="{{ asset('vendor/tailadmin/favicon.ico') }}">
        @endif

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            #rent-slider-container {
                display: flex;
                align-items: stretch;
                gap: 24px;
                overflow-x: auto;
                scroll-behavior: smooth;
                scroll-snap-type: x mandatory;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            #rent-slider-container::-webkit-scrollbar {
                display: none;
            }
            #rent-slider-container .rent-card {
                width: 100%;
                flex-shrink: 0;
                scroll-snap-align: start;
                display: flex;
                flex-direction: column;
                height: auto !important;
            }
            @media (min-width: 768px) {
                #rent-slider-container .rent-card {
                    width: calc(33.333% - 16px) !important;
                }
            }
        </style>
    </head>
    <body class="bg-[#f3f7fc] text-slate-700">
        @include('partials.layout.sticky-header')

        <header class="relative isolate overflow-visible bg-[#0f355e]" data-sticky-trigger>
            <img src="{{ asset('images/hero-home.webp') }}" alt="Hero home" class="absolute inset-0 h-full w-full object-cover" onerror="this.style.display='none'">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(12,46,78,0.74)_0%,rgba(11,47,79,0.84)_52%,rgba(9,39,66,0.9)_100%)]"></div>

            @include('partials.layout.topbar')

            <div class="section-shell relative z-20 py-4 sm:py-6">
                @include('partials.layout.navbar')

                <div class="pb-14 pt-10 text-center sm:pb-16 sm:pt-12 lg:pb-18 lg:pt-16">
                    <h1 class="heading-display mx-auto max-w-4xl text-[2.45rem] font-extrabold leading-[1.08] text-white sm:text-[3.7rem]">Find Your Dream Home</h1>
                    <p class="mx-auto mt-6 max-w-4xl text-[0.9rem] font-semibold leading-relaxed text-white/95 sm:text-[1.2rem]">
                        We are recognized for exceeding client expectations and delivering great results through dedication,
                        ease of process, and extraordinary services to our worldwide clients.
                    </p>
                </div>
            </div>

            @include('partials.home.search-form')
        </header>

        <section class="bg-[#eef5fc] py-12 sm:py-16">
            <div class="section-shell">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="section-title">Properties by Area</h2>
                    <p class="section-copy mx-auto mt-4 max-w-2xl">
                        Highlight the best of your properties by using the List Category shortcode. You can list categories,
                        types, cities, areas and states of your choice.
                    </p>
                </div>

                <div class="mx-auto mt-10 max-w-6xl rounded-2xl bg-white px-5 py-8 shadow-[0_20px_70px_rgba(16,45,86,0.08)] sm:px-8 sm:py-10 lg:px-10">
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                        @php
                            $cityFallbackImages = [
                                'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1479839672679-a46483c0e7c8?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=200&q=80',
                                'https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&w=200&q=80'
                            ];
                        @endphp
                        @foreach($cities as $city)
                            @php
                                $cityImage = $cityFallbackImages[$loop->index] ?? $cityFallbackImages[0];
                            @endphp
                            <a href="{{ route('home') }}/properties?city={{ $city->id }}" class="flex items-center gap-4 group">
                                <img src="{{ $cityImage }}" alt="{{ $city->name }}" class="h-20 w-20 rounded-sm object-cover shadow-sm transition duration-300 group-hover:opacity-85">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-600 transition">{{ $city->name }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $city->properties_count }} {{ Str::plural('listing', $city->properties_count) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white py-12 sm:py-16">
            <div class="section-shell">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="section-title">Latest Properties</h2>
                    <p class="section-copy mx-auto mt-4 max-w-3xl">
                        These are the latest properties in the sales category. You can create the list using
                        the latest listing shortcode and show items by specific categories.
                    </p>
                </div>

                <div class="mx-auto mt-6 flex w-fit flex-wrap justify-center gap-2 rounded-lg bg-slate-50 p-1 shadow-sm ring-1 ring-slate-200">
                    <button data-filter="all" class="filter-tab-btn rounded-md bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition">All</button>
                    <button data-filter="sale" class="filter-tab-btn rounded-md px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 transition">Sale</button>
                    <button data-filter="rent" class="filter-tab-btn rounded-md px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 transition">Rent</button>
                    <button data-filter="lease" class="filter-tab-btn rounded-md px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 transition">Lease</button>
                </div>

                <div id="latest-properties-grid" class="mx-auto mt-6 grid max-w-6xl gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($latestProperties as $prop)
                        @php
                            $primaryImage = $prop->images?->firstWhere('is_primary', true) ?? $prop->images?->first();
                            $imageUrl = $primaryImage 
                                ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path)
                                : 'https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=900&q=80';
                        @endphp
                        <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg flex flex-col h-full"
                            data-purpose="{{ strtolower($prop->property_purpose) }}">
                            <a href="{{ route('property-detail', $prop->slug) }}">
                                <img src="{{ $imageUrl }}" alt="{{ $prop->title }}" referrerpolicy="no-referrer" class="h-52 w-full object-cover">
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
                                    @if($prop->featured)
                                        <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                                    @else
                                        <span></span>
                                    @endif
                                    <div class="flex gap-1">
                                        <span class="rounded bg-blue-600 px-2 py-1">{{ ucfirst($prop->property_purpose ?: 'Sale') }}</span>
                                        <span class="rounded bg-blue-500 px-2 py-1">{{ ucfirst($prop->listing_type ?: 'Active') }}</span>
                                    </div>
                                </div>
                                <h3 class="property-card-title mt-4 hover:text-blue-600 transition">
                                    <a href="{{ route('property-detail', $prop->slug) }}">
                                        {{ $prop->title }}
                                    </a>
                                </h3>
                                <p class="property-card-price mt-1">
                                    {{ is_numeric($prop->price) ? format_price($prop->price) : ($prop->price ?: 'Contact for Price') }}{{ $prop->property_purpose === 'rent' ? ' / month' : '' }}
                                </p>
                                <p class="property-card-copy mt-2 line-clamp-2">{{ Str::limit($prop->description, 100) }}</p>
                                <p class="property-card-meta mt-auto pt-3">
                                    {{ $prop->locality }}{{ $prop->city?->name ? ', ' . $prop->city->name : '' }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}/properties" class="inline-block rounded-md bg-[#0f6fcb] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#0c5eac] transition">View More Properties</a>
                </div>
            </div>
        </section>

        <section class="bg-[#eef5fc] py-12 sm:py-16">
            <div class="section-shell">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="section-title">Properties by Category</h2>
                    <p class="section-copy mx-auto mt-4 max-w-3xl">
                        Highlight the best of your properties by using the List Category shortcode. You can
                        list specific properties categories, types, cities, areas.
                    </p>
                </div>

                <div class="mx-auto mt-6 grid max-w-6xl grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:grid-rows-2">
                    @php
                        $fallbackImages = [
                            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                            'https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1600047509782-20d39509f26d?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1617104678098-de229db51175?auto=format&fit=crop&w=1200&q=80'
                        ];
                    @endphp
                    @foreach($categories as $cat)
                        @php
                            $gridClass = '';
                            if ($loop->index === 0) {
                                $gridClass = 'lg:col-span-2 lg:row-span-1';
                            } elseif ($loop->index === 5) {
                                $gridClass = 'lg:col-span-2';
                            }
                            $catImage = $cat->image 
                                ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($cat->image) 
                                : ($fallbackImages[$loop->index] ?? $fallbackImages[0]);
                        @endphp
                        <article class="relative min-h-72 overflow-hidden rounded-sm bg-slate-200 shadow-sm {{ $gridClass }}">
                            <a href="{{ route('home') }}/properties?category={{ $cat->id }}">
                                <img src="{{ $catImage }}" alt="{{ $cat->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-300 hover:scale-105">
                                <div class="absolute inset-0 bg-black/25"></div>
                                <div class="absolute inset-0 p-4 text-white">
                                    <h3 class="text-lg font-semibold">{{ $cat->name }}</h3>
                                    <p class="absolute bottom-4 left-4 text-sm font-medium">{{ $cat->properties_count }} listings</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-[#f5f7fb] py-12 sm:py-16">
            <div class="section-shell relative">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="section-title">Properties for Rent</h2>
                    <p class="section-copy mx-auto mt-4 max-w-3xl">
                        These are the latest properties in the sales category. You can create the list using
                        the "latest listing shortcode" and show items by specific categories.
                    </p>
                </div>

                <button class="absolute left-0 top-1/2 z-10 -translate-y-1/2 -translate-x-5 rounded bg-[#e8edf7] p-2 text-[#2f6dd7] shadow-md hover:bg-[#2f6dd7] hover:text-white transition duration-300 md:block hidden" aria-label="Previous slide">&larr;</button>
                <button class="absolute right-0 top-1/2 z-10 -translate-y-1/2 translate-x-5 rounded bg-[#e8edf7] p-2 text-[#2f6dd7] shadow-md hover:bg-[#2f6dd7] hover:text-white transition duration-300 md:block hidden" aria-label="Next slide">&rarr;</button>

                <div id="rent-slider-container" class="mx-auto mt-8 flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar max-w-6xl pb-4">
                    @foreach($rentProperties as $prop)
                        @php
                            $primaryImage = $prop->images?->firstWhere('is_primary', true) ?? $prop->images?->first();
                            $imageUrl = $primaryImage 
                                ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path)
                                : 'https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=900&q=80';
                        @endphp
                        <article class="rent-card overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm flex flex-col h-full">
                            <div class="relative">
                                <a href="{{ route('property-detail', $prop->slug) }}">
                                    <img src="{{ $imageUrl }}" alt="{{ $prop->title }}" referrerpolicy="no-referrer" class="h-56 w-full object-cover">
                                </a>
                                @if($prop->featured)
                                    <div class="absolute left-3 top-3 flex items-center gap-1 text-[10px] font-semibold uppercase text-white">
                                        <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                                    </div>
                                @endif
                                <div class="absolute right-3 top-3 flex items-center gap-1 text-[10px] font-semibold uppercase text-white">
                                    <span class="rounded bg-blue-600 px-2 py-1">{{ ucfirst($prop->property_purpose ?: 'Rent') }}</span>
                                    <span class="rounded bg-blue-500 px-2 py-1">{{ ucfirst($prop->status ?: 'Active') }}</span>
                                </div>
                                <p class="absolute bottom-3 left-3 text-xs font-semibold text-white">
                                    {{ $prop->locality }}{{ $prop->city?->name ? ', ' . $prop->city->name : '' }}
                                </p>
                            </div>
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl hover:text-blue-600 transition">
                                    <a href="{{ route('property-detail', $prop->slug) }}">
                                        {{ $prop->title }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-lg font-semibold text-blue-600">
                                    {{ is_numeric($prop->price) ? format_price($prop->price) : ($prop->price ?: 'Contact for Price') }} / {{ $prop->price_type ?? 'month' }}
                                </p>
                                <p class="mt-2 text-sm leading-6 text-slate-500 line-clamp-3">{{ Str::limit($prop->description, 100) }}</p>
                                <div class="mt-auto pt-4">
                                    <div class="flex items-center gap-4 border-b border-slate-100 pb-3 text-xs text-slate-500">
                                        <span>{{ $prop->bedrooms ?? 0 }} Beds</span>
                                        <span>{{ $prop->bathrooms ?? 0 }} Baths</span>
                                        <span>{{ $prop->size ? $prop->size . ' ft²' : 'N/A' }}</span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <p class="text-sm font-semibold text-slate-700">Listing Agent</p>
                                        <div class="flex gap-1 text-slate-400">
                                            <span class="rounded border border-slate-200 px-2 py-1">+</span>
                                            <span class="rounded border border-slate-200 px-2 py-1">♡</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div id="rent-slider-dots" class="mt-6 flex justify-center gap-3 hidden">
                    <button data-slide="0" class="h-2.5 w-2.5 rounded-full bg-[#2f6dd7] transition duration-300 cursor-pointer" aria-label="Go to page 1"></button>
                    <button data-slide="1" class="h-2.5 w-2.5 rounded-full bg-slate-300 transition duration-300 cursor-pointer" aria-label="Go to page 2"></button>
                    <button data-slide="2" class="h-2.5 w-2.5 rounded-full bg-slate-300 transition duration-300 cursor-pointer" aria-label="Go to page 3"></button>
                    <button data-slide="3" class="h-2.5 w-2.5 rounded-full bg-slate-300 transition duration-300 cursor-pointer" aria-label="Go to page 4"></button>
                </div>
            </div>
        </section>

        <section class="bg-[#eef5fc] py-8 sm:py-10">
            <div class="section-shell">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="section-title">Featured Properties</h2>
                    <p class="section-copy mx-auto mt-3 max-w-3xl">
                        Here are two listings displayed with the featured property shortcode, which you
                        can use when you have some special properties to present.
                    </p>
                </div>

                <div class="mx-auto mt-6 grid max-w-6xl gap-6 lg:grid-cols-2">
                    @foreach($featuredProperties as $prop)
                        @php
                            $imagesList = [];
                            if ($prop->images && $prop->images->isNotEmpty()) {
                                foreach ($prop->images as $img) {
                                    $imagesList[] = \App\Classes\ImageuploadHelper::getUploadedFileUrl($img->path);
                                }
                            } else {
                                $imagesList = [
                                    'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=900&q=80',
                                    'https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=900&q=80',
                                    'https://images.unsplash.com/photo-1600047509782-20d39509f26d?auto=format&fit=crop&w=900&q=80'
                                ];
                            }
                            $imagesAttr = implode('|', $imagesList);
                            $firstImage = $imagesList[0];
                        @endphp
                        <article class="featured-slider overflow-hidden rounded-sm bg-white shadow-sm ring-1 ring-slate-200 h-auto md:h-[300px]"
                            data-images="{{ $imagesAttr }}">
                            <div class="grid md:grid-cols-2">
                                <div class="relative">
                                    <a href="{{ route('property-detail', $prop->slug) }}">
                                        <img src="{{ $firstImage }}" alt="{{ $prop->title }}" referrerpolicy="no-referrer" class="featured-slide-image h-full min-h-60 w-full object-cover transition-opacity duration-300">
                                    </a>
                                    @if(count($imagesList) > 1)
                                        <button type="button" class="featured-slide-prev absolute left-3 top-1/2 -translate-y-1/2 rounded bg-black/25 px-2 py-1 text-white">&lt;</button>
                                        <button type="button" class="featured-slide-next absolute right-3 top-1/2 -translate-y-1/2 rounded bg-black/25 px-2 py-1 text-white">&gt;</button>
                                    @endif
                                    <img src="https://images.unsplash.com/photo-1545167622-3a6ac756afa4?auto=format&fit=crop&w=120&q=80" alt="Agent" class="absolute bottom-3 left-3 h-12 w-12 rounded-full border-2 border-white object-cover shadow-sm">
                                </div>
                                <div class="flex flex-col p-5">
                                    <h3 class="text-lg font-semibold tracking-[-0.012em] text-[#1f2a44] sm:text-xl hover:text-blue-600 transition">
                                        <a href="{{ route('property-detail', $prop->slug) }}">
                                            {{ $prop->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-2 text-2xl font-semibold text-blue-600">{{ is_numeric($prop->price) ? format_price($prop->price) : ($prop->price ?: 'Contact for Price') }}</p>
                                    <p class="mt-3 text-[14px] leading-6 text-[#5f6f8c] line-clamp-3">{{ Str::limit($prop->description, 100) }}</p>
                                    <div class="mt-4 flex gap-2">
                                        <span class="rounded border border-slate-200 px-3 py-1.5 text-slate-400 text-xs">⇄</span>
                                        <span class="rounded border border-slate-200 px-3 py-1.5 text-slate-400 text-xs">♡</span>
                                    </div>
                                    <div class="mt-auto border-t border-slate-200 pt-4 text-xs text-slate-500">
                                        <div class="flex items-center gap-4">
                                            <span>{{ $prop->bedrooms ?? 0 }} Beds</span>
                                            <span>{{ $prop->bathrooms ?? 0 }} Baths</span>
                                            <span>{{ $prop->size ? $prop->size . ' ft²' : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        @if($adProperty)
        <style>
            .ad-banner-height {
                height: 380px;
            }
            .ad-banner-card {
                position: absolute;
                left: 1.5rem;
                right: 1.5rem;
                bottom: 0;
                background-color: #ffffff;
                padding: 1.5rem;
                border-top-left-radius: 0.75rem;
                border-top-right-radius: 0.75rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                z-index: 10;
            }
            @media (min-width: 768px) {
                .ad-banner-card {
                    left: 4rem;
                    right: auto;
                    width: 380px;
                }
            }
            @media (min-width: 1024px) {
                .ad-banner-height {
                    height: 500px;
                }
            }
        </style>
        <section class="w-full bg-white py-0">
            <div class="grid w-full gap-0 lg:grid-cols-2">
                @php
                    $primaryImage = $adProperty->images?->firstWhere('is_primary', true) ?? $adProperty->images?->first();
                    $imageUrl = $primaryImage 
                        ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path)
                        : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1400&q=80';

                    $gallery = $adProperty->images->where('is_primary', false)->values();
                    $fallbackAdImages = [
                        'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1000&q=80',
                        'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1000&q=80',
                        'https://images.unsplash.com/photo-1617104678098-de229db51175?auto=format&fit=crop&w=1000&q=80',
                        'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1000&q=80'
                    ];
                @endphp
                <article class="relative ad-banner-height overflow-hidden" style="position: relative; overflow: hidden;">
                    <img src="{{ $imageUrl }}" alt="{{ $adProperty->title }}" referrerpolicy="no-referrer" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.45), rgba(0,0,0,0.15), transparent); pointer-events: none; z-index: 5;"></div>

                    <div class="ad-banner-card">
                        <p class="text-xs font-semibold text-blue-600 sm:text-sm">
                            {{ $adProperty->price && is_numeric($adProperty->price) ? '$' . number_format((float) $adProperty->price) : ($adProperty->price ?: 'Contact for Price') }}{{ $adProperty->property_purpose === 'rent' ? ' / month' : '' }}
                        </p>
                        <h3 class="mt-2 text-xl font-bold leading-tight tracking-[-0.015em] text-[#1f2a44] sm:text-2xl hover:text-blue-600 transition">{{ $adProperty->title }}</h3>
                        <p class="mt-2 text-xs font-medium text-slate-400 uppercase tracking-wide">
                            {{ $adProperty->bedrooms ?? 0 }} BD · {{ $adProperty->bathrooms ?? 0 }} BA · {{ $adProperty->size ? $adProperty->size . ' ft²' : 'N/A' }}
                        </p>
                        <p class="mt-3 text-sm leading-relaxed text-[#5f6f8c]">
                            {{ Str::limit($adProperty->description, 140) }}
                        </p>
                        <a href="{{ route('property-detail', $adProperty->slug) }}" class="mt-4 inline-flex items-center text-xs font-bold text-slate-800 hover:text-blue-600 transition">discover more <span class="ml-1.5 text-base leading-none">&rsaquo;</span></a>
                    </div>
                </article>

                <div class="grid ad-banner-height grid-cols-2 grid-rows-2 gap-[2px] bg-white">
                    @for($i = 0; $i < 4; $i++)
                        @php
                            $imgUrl = isset($gallery[$i]) 
                                ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($gallery[$i]->path) 
                                : $fallbackAdImages[$i];
                        @endphp
                        <img src="{{ $imgUrl }}" alt="Interior gallery image" class="h-full w-full object-cover">
                    @endfor
                </div>
            </div>
        </section>
        @endif

        @if(count($featuredAgents) > 0)
        <section class="bg-[#eef5fc] py-12 sm:py-16">
            <div class="section-shell">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="section-title">Real Estate Agents</h2>
                    <p class="section-copy mx-auto mt-4 max-w-3xl">
                        With the "list agents shortcode" you can show your agents in any page, alongside
                        with their contact details and link to their agent profile.
                    </p>
                </div>

                <div class="mx-auto mt-8 grid max-w-6xl gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($featuredAgents as $agent)
                        @php
                            $agentPhoto = $agent->profile_pic 
                                ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($agent->profile_pic)
                                : 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=900&q=80';
                        @endphp
                        <article class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg flex flex-col h-full">
                            <div class="relative aspect-[4/3] overflow-hidden bg-[#f1f3f8]">
                                <a href="{{ route('agent-detail', ['slug' => \Illuminate\Support\Str::slug($agent->name)]) }}">
                                    <img src="{{ $agentPhoto }}" alt="{{ $agent->name }}" class="h-full w-full object-cover object-center" loading="lazy">
                                </a>
                                @if($agent->agentMeta?->is_featured)
                                    <span class="absolute top-3 left-3 rounded bg-emerald-500 px-3 py-1 text-xs font-semibold text-white">Featured</span>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl hover:text-blue-600 transition">
                                    <a href="{{ route('agent-detail', ['slug' => \Illuminate\Support\Str::slug($agent->name)]) }}">
                                        {{ $agent->name }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-[#6a7892] sm:text-base">{{ $agent->agentMeta?->title ?? 'Realtor' }}</p>
                                <p class="mt-4 text-[15px] leading-7 text-[#5f6f8c] sm:text-base flex-grow">
                                    {{ Str::limit($agent->agentMeta?->about_me, 120) }}
                                </p>
                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <div class="flex items-center gap-2 text-slate-500">
                                        @if($agent->agentMeta?->facebook)
                                            <a href="{{ $agent->agentMeta->facebook }}" target="_blank" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Facebook"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 9H16V6h-2.5C11.6 6 10 7.6 10 9.5V12H8v3h2v6h3v-6h2.3l.7-3H13v-2.2c0-.5.2-.8.5-.8z"/></svg></a>
                                        @endif
                                        @if($agent->agentMeta?->twitter)
                                            <a href="{{ $agent->agentMeta->twitter }}" target="_blank" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="X"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H22l-6.8 7.8L23 21h-6.2l-4.9-6.4L6.3 21H3.1l7.3-8.3L1 3h6.3l4.4 5.9L18.9 3z"/></svg></a>
                                        @endif
                                        @if($agent->agentMeta?->linkedin)
                                            <a href="{{ $agent->agentMeta->linkedin }}" target="_blank" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="LinkedIn"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5A1.9 1.9 0 1 0 6.9 4.7a1.9 1.9 0 0 0 0 3.8zM5.2 10h3.4v9.3H5.2V10zm5.4 0H14v1.3h.1c.5-.9 1.6-1.6 3.2-1.6 3.5 0 4.1 2.3 4.1 5.3v4.3H18v-3.8c0-.9 0-2.1-1.3-2.1s-1.5 1-1.5 2v4H12V10z"/></svg></a>
                                        @endif
                                        @if($agent->agentMeta?->instagram)
                                            <a href="{{ $agent->agentMeta->instagram }}" target="_blank" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Instagram"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 8.2A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4zM18.4 6.9a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/><path d="M12 2.8c3 0 3.3 0 4.4.1 1 .1 1.5.2 1.8.4.5.2.8.4 1.1.7.3.3.5.6.7 1.1.2.3.3.9.4 1.8.1 1.1.1 1.4.1 4.4s0 3.3-.1 4.4c-.1 1-.2 1.5-.4 1.8-.2.5-.4.8-.7 1.1-.3.3-.6.5-1.1.7-.3.2-.9.3-1.8.4-1.1.1-1.4.1-4.4.1s-3.3 0-4.4-.1c-1-.1-1.5-.2-1.8-.4a3 3 0 0 1-1.1-.7 3 3 0 0 1-.7-1.1c-.2-.3-.3-.9-.4-1.8-.1-1.1-.1-1.4-.1-4.4s0-3.3.1-4.4c.1-1 .2-1.5.4-1.8.2-.5.4-.8.7-1.1.3-.3.6-.5 1.1-.7.3-.2.9-.3 1.8-.4 1.1-.1 1.4-.1 4.4-.1zm0-1.8c-3.1 0-3.5 0-4.6.1-1.1.1-1.9.2-2.6.5a4.8 4.8 0 0 0-1.7 1.1A4.8 4.8 0 0 0 2 4.4c-.3.7-.4 1.5-.5 2.6C1.4 8 1.4 8.4 1.4 11.5S1.4 15 1.5 16c.1 1.1.2 1.9.5 2.6a4.8 4.8 0 0 0 1.1 1.7 4.8 4.8 0 0 0 1.7 1.1c.7.3 1.5.4 2.6.5 1.1.1 1.4.1 4.6.1 3.1 0 3.5 0 4.6-.1 1.1-.1 1.9-.2 2.6-.5a4.8 4.8 0 0 0 1.7-1.1 4.8 4.8 0 0 0 1.1-1.7c.3-.7.4-1.5.5-2.6.1-1.1.1-1.4.1-4.6s0-3.5-.1-4.6c-.1-1.1-.2-1.9-.5-2.6a4.8 4.8 0 0 0-1.1-1.7 4.8 4.8 0 0 0-1.7-1.1c-.7-.3-1.5-.4-2.6-.5C15.5 1 15.2 1 12 1z"/></svg></a>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-500">
                                        @if($agent->phone)
                                            <a href="tel:{{ $agent->phone }}" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Call"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.3 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C11.9 21 3 12.1 3 1.9 3 1.3 3.4.9 4 .9h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.3 1l-2.2 2.3z"/></svg></a>
                                        @endif
                                        <a href="mailto:{{ $agent->email }}" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Email"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg></a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    <a href="{{ route('agents') }}" class="rounded bg-[#0f6fcb] px-6 py-3 text-base font-semibold text-white transition hover:bg-[#0c5eac] shadow-md flex items-center gap-2">
                        View All Agents
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </section>
        @endif

        <section class="bg-[#eef5fc] py-12 sm:py-16">
            <div class="section-shell">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="section-title">Testimonials</h2>
                    <p class="section-copy mx-auto mt-4 max-w-3xl">
                        Publish the best of your client testimonials and let the world know what a great
                        agent or real estate agency you are. Testimonials build trust.
                    </p>
                </div>
                <div class="mx-auto mt-8 grid max-w-6xl gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($testimonials as $t)
                        @php
                            $imageUrl = $t->image;
                            if ($imageUrl && !Str::startsWith($imageUrl, ['http://', 'https://'])) {
                                $imageUrl = '/storage/' . $imageUrl;
                            }
                            if (!$imageUrl) {
                                $imageUrl = 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80';
                            }
                        @endphp
                        <article class="rounded bg-white p-6 shadow-sm ring-1 ring-slate-200 flex flex-col h-full">
                            <div class="flex items-center gap-3">
                                <img src="{{ $imageUrl }}" alt="{{ $t->name }}" class="h-10 w-10 rounded-full object-cover border">
                                <div>
                                    <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">{{ $t->name }}</h3>
                                    <p class="text-sm text-slate-500">{{ $t->designation }}</p>
                                </div>
                            </div>
                            <p class="mt-5 text-[15px] leading-7 text-[#5f6f8c] sm:text-base flex-1">
                                {{ $t->content }}
                            </p>
                            <p class="mt-4 text-amber-400">
                                {{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="w-full bg-[#103a66] py-12 sm:py-16">
            <div class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center text-white">
                    <h2 class="text-3xl font-bold leading-tight tracking-[-0.015em] sm:text-4xl">
                        Contact us today if you'd like to know more
                        about how we help buy, sell or rent your home
                    </h2>
                </div>

                <div class="mx-auto mt-6 max-w-3xl rounded-sm bg-white p-6 shadow-2xl shadow-black/20 sm:p-8">
                    <div class="text-center">
                        <h3 class="text-xl font-semibold tracking-[-0.015em] text-[#1f2a44] sm:text-2xl">Schedule a meeting with our team</h3>
                        <p class="mx-auto mt-4 max-w-xl text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                            Our experts and developers would love to contribute their
                            expertise and insights to your real estate plans.
                        </p>
                    </div>

                    <form id="meeting-form" class="mt-6 space-y-3" novalidate>
                        @csrf
                        <input type="hidden" name="type" value="contact">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <input type="text" name="name" placeholder="Name" required class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base">
                                <span class="error-msg text-xs text-red-500 hidden mt-1">Name is required.</span>
                            </div>
                            <div>
                                <input type="text" name="phone" placeholder="Mobile" class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base">
                                <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter a valid phone number.</span>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <input type="email" name="email" placeholder="Email" required class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base">
                                <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter a valid email address.</span>
                            </div>
                            <div>
                                <select name="enq_for" class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base cursor-pointer">
                                    <option value="">Select Inquiry Type</option>
                                    <option value="sale">Sale</option>
                                    <option value="rent">Rent</option>
                                    <option value="lease">Lease</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <textarea name="message" rows="4" placeholder="Message" class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base"></textarea>
                        </div>

                        <button type="submit" class="w-full rounded bg-[#0f6fcb] px-6 py-3 text-base font-semibold text-white transition hover:bg-[#0c5eac] sm:text-lg flex items-center justify-center gap-2">
                            <span class="submit-text">Send Email</span>
                            <svg class="spinner hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        @include('partials.layout.footer')

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="module">
        $(document).ready(function() {
            // Equalize card heights
            function equalizeRentCardHeights() {
                const cards = document.querySelectorAll('#rent-slider-container .rent-card');
                let maxHeight = 0;
                cards.forEach(function(card) {
                    card.style.height = 'auto';
                });
                cards.forEach(function(card) {
                    const height = card.offsetHeight;
                    if (height > maxHeight) {
                        maxHeight = height;
                    }
                });
                cards.forEach(function(card) {
                    card.style.setProperty('height', maxHeight + 'px', 'important');
                });
            }

            equalizeRentCardHeights();
            window.addEventListener('resize', equalizeRentCardHeights);
            // Run again after images/assets load to ensure final height calculation
            window.addEventListener('load', equalizeRentCardHeights);

            // Rent slider controls
            const rentContainer = document.getElementById('rent-slider-container');
            const rentPrevBtn = document.querySelector('[aria-label="Previous slide"]');
            const rentNextBtn = document.querySelector('[aria-label="Next slide"]');
            const rentDots = document.querySelectorAll('#rent-slider-dots button');
            
            if (rentContainer && rentPrevBtn && rentNextBtn) {
                rentPrevBtn.addEventListener('click', function() {
                    rentContainer.scrollBy({ left: -rentContainer.clientWidth, behavior: 'smooth' });
                });
                rentNextBtn.addEventListener('click', function() {
                    rentContainer.scrollBy({ left: rentContainer.clientWidth, behavior: 'smooth' });
                });
            }

            // Dots click handling
            rentDots.forEach(function(dot) {
                dot.addEventListener('click', function() {
                    const slideIndex = parseInt(this.getAttribute('data-slide'));
                    if (rentContainer) {
                        rentContainer.scrollTo({
                            left: slideIndex * rentContainer.clientWidth,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Update active dot on scroll
            if (rentContainer) {
                rentContainer.addEventListener('scroll', function() {
                    const scrollLeft = rentContainer.scrollLeft;
                    const containerWidth = rentContainer.clientWidth || 1;
                    const activeIndex = Math.round(scrollLeft / containerWidth);
                    
                    rentDots.forEach(function(dot, index) {
                        if (index === activeIndex) {
                            dot.classList.remove('bg-slate-300');
                            dot.classList.add('bg-[#2f6dd7]');
                        } else {
                            dot.classList.remove('bg-[#2f6dd7]');
                            dot.classList.add('bg-slate-300');
                        }
                    });
                });
            }

            // Filter latest properties
            $('.filter-tab-btn').on('click', function() {
                const targetPurpose = $(this).attr('data-filter');
                
                // Toggle active classes
                $('.filter-tab-btn').removeClass('bg-white text-slate-700 shadow-sm').addClass('text-slate-500 hover:text-slate-700');
                $(this).addClass('bg-white text-slate-700 shadow-sm').removeClass('text-slate-500 hover:text-slate-700');
                
                // Show / Hide elements
                if (targetPurpose === 'all') {
                    $('#latest-properties-grid article').fadeIn(250);
                } else {
                    $('#latest-properties-grid article').each(function() {
                        const itemPurpose = $(this).attr('data-purpose');
                        if (itemPurpose === targetPurpose) {
                            $(this).fadeIn(250);
                        } else {
                            $(this).fadeOut(150);
                        }
                    });
                }
            });

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function clearErrors(form) {
                $(form).find('.error-msg').addClass('hidden');
                $(form).find('input, textarea').removeClass('border-red-500').addClass('border-slate-200');
            }

            function showFieldError(inputEl, errorText) {
                inputEl.addClass('border-red-500').removeClass('border-slate-200');
                const errorEl = inputEl.parent().find('.error-msg');
                if (errorEl.length) {
                    if (errorText) {
                        errorEl.text(errorText);
                    }
                    errorEl.removeClass('hidden');
                }
            }

            $('#meeting-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const $form = $(form);
                clearErrors(form);

                let isValid = true;

                const nameInput = $form.find('input[name="name"]');
                if (!nameInput.val().trim()) {
                    showFieldError(nameInput, 'Name is required.');
                    isValid = false;
                }

                const emailInput = $form.find('input[name="email"]');
                const emailVal = emailInput.val().trim();
                if (!emailVal) {
                    showFieldError(emailInput, 'Email is required.');
                    isValid = false;
                } else if (!emailRegex.test(emailVal)) {
                    showFieldError(emailInput, 'Please enter a valid email address.');
                    isValid = false;
                }

                const phoneInput = $form.find('input[name="phone"]');
                const phoneVal = phoneInput.val().trim();
                if (phoneVal && phoneVal.length > 20) {
                    showFieldError(phoneInput, 'Phone number must be under 20 characters.');
                    isValid = false;
                }

                if (!isValid) return;

                const submitBtn = $form.find('button[type="submit"]');
                const spinner = submitBtn.find('.spinner');
                const submitText = submitBtn.find('.submit-text');

                submitBtn.prop('disabled', true);
                spinner.removeClass('hidden');
                if (submitText.length) submitText.css('opacity', '0.5');

                // Prepare form data
                const formData = $form.serialize();

                $.ajax({
                    url: "{{ route('save_enq') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        submitBtn.prop('disabled', false);
                        spinner.addClass('hidden');
                        if (submitText.length) submitText.css('opacity', '1');

                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message || 'Meeting request submitted successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#0f6fcb'
                            });
                            
                            // Reset inputs
                            $form.find('input[type="text"], input[type="email"], select, textarea').val('');
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Submission failed. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#0f6fcb'
                            });
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false);
                        spinner.addClass('hidden');
                        if (submitText.length) submitText.css('opacity', '1');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            Swal.fire({
                                title: 'Validation Error',
                                text: 'Please correct the errors in the form.',
                                icon: 'warning',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#0f6fcb'
                            });

                            Object.keys(errors).forEach(function(field) {
                                const inputEl = $form.find(`[name="${field}"]`);
                                if (inputEl.length) {
                                    showFieldError(inputEl, errors[field][0]);
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred. Please try again later.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#0f6fcb'
                            });
                        }
                    }
                });
            });
        });
        </script>
    </body>
</html>
