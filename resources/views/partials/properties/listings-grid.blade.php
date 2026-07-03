@php
    $listingProperties = [
        [
            'image' => 'https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=900&q=80',
            'title' => 'Luxury House in Greenville',
            'priceText' => '$860,000',
            'description' => 'This property is mostly wooded and sits high on a hilltop overlooking the Mohawk River Valley.',
            'location' => 'Greenville, Jersey City',
            'agent' => 'John Collins',
            'agentAvatar' => 'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=120&q=80',
            'metaBeds' => 5,
            'metaBaths' => 6,
            'metaSize' => '190 ft²',
            'metaMedia' => 7,
            'badgeOne' => 'Sales',
            'badgeTwo' => 'Active',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80',
            'title' => 'Modern Condo for Sale',
            'priceText' => '$150,000',
            'description' => 'Beautiful, updated, ground level Co-op apartment in the desirable Bay Terrace neighborhood ...',
            'location' => 'Manhattan, New York',
            'agent' => 'Michael Sutther',
            'agentAvatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
            'metaBeds' => 2,
            'metaBaths' => 2,
            'metaSize' => '200 ft²',
            'metaMedia' => 7,
            'badgeOne' => 'Sales',
            'badgeTwo' => 'Open House',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=900&q=80',
            'title' => 'Apartment with Subunits',
            'priceText' => '$999 / month',
            'description' => 'Seamless street international co-op features an educational children welcome ...',
            'location' => 'Queens, New York',
            'agent' => 'Lena Smith',
            'agentAvatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80',
            'metaBeds' => 5,
            'metaBaths' => 6,
            'metaSize' => '190 ft²',
            'metaMedia' => 7,
            'badgeOne' => 'Rentals',
            'badgeTwo' => 'Active',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=900&q=80',
            'title' => 'Three Room Apartment',
            'priceText' => '$550,000',
            'description' => 'Beautiful huge 1 Family House in heart of Westbury, Newly Renovated with New Roof, Floors ...',
            'location' => 'Manhattan, New York',
            'agent' => 'Mike Butler',
            'agentAvatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=120&q=80',
            'metaBeds' => 5,
            'metaBaths' => 2,
            'metaSize' => '450 ft²',
            'metaMedia' => 7,
            'badgeOne' => 'Sales',
            'badgeTwo' => 'New',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80',
            'title' => 'Suburb Home for Sale',
            'priceText' => '$1,090,000',
            'description' => 'Minimal Reserve 2000 acres of this grand setting (on just one of 15 acres) and priced to ...',
            'location' => 'The Heights, Jersey City',
            'agent' => 'Lara Mack',
            'agentAvatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
            'metaBeds' => 5,
            'metaBaths' => 5,
            'metaSize' => '2200 ft²',
            'metaMedia' => 7,
            'badgeOne' => 'Invest',
            'badgeTwo' => 'New',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80',
            'title' => 'Gorgeous Home for Sale in Jersey',
            'priceText' => '$770,000',
            'description' => 'This property is mostly wooded and sits high on a hilltop overlooking the Mohawk River Val ...',
            'location' => 'Downtown, Jersey City',
            'agent' => 'Maria Miller',
            'agentAvatar' => 'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?auto=format&fit=crop&w=120&q=80',
            'metaBeds' => 5,
            'metaBaths' => 6,
            'metaSize' => '190 ft²',
            'metaMedia' => 7,
            'badgeOne' => 'Sales',
            'badgeTwo' => 'Video',
        ],
    ];

    $sidebarSections = [
        [
            'title' => 'List by Categories',
            'items' => [
                ['name' => 'Apartments', 'count' => 17],
                ['name' => 'Condos', 'count' => 8],
                ['name' => 'Houses', 'count' => 5],
                ['name' => 'Industrial', 'count' => 1],
                ['name' => 'Land', 'count' => 1],
                ['name' => 'Offices', 'count' => 2],
                ['name' => 'Retail', 'count' => 4],
                ['name' => 'Villas', 'count' => 4],
            ],
        ],
        [
            'title' => 'List by Types',
            'items' => [
                ['name' => 'Sales', 'count' => 20],
                ['name' => 'Rentals', 'count' => 18],
                ['name' => 'Invest', 'count' => 4],
            ],
        ],
        [
            'title' => 'List by Cities',
            'items' => [
                ['name' => 'Jersey City', 'count' => 25],
                ['name' => 'New York', 'count' => 17],
            ],
        ],
        [
            'title' => 'List by Areas',
            'items' => [
                ['name' => 'Bayonne', 'count' => 5],
                ['name' => 'Greenville', 'count' => 17],
                ['name' => 'Manhattan', 'count' => 8],
                ['name' => 'Queens', 'count' => 4],
                ['name' => 'The Heights', 'count' => 3],
                ['name' => 'Upper East Side', 'count' => 3],
                ['name' => 'West Side', 'count' => 1],
                ['name' => 'West Village', 'count' => 1],
            ],
        ],
    ];

    $topDropdowns = [
        [
            'label' => 'Types',
            'options' => ['Sales', 'Rentals', 'Invest'],
        ],
        [
            'label' => 'Categories',
            'options' => ['Apartments', 'Condos', 'Houses', 'Villas'],
        ],
        [
            'label' => 'States',
            'options' => ['New York', 'New Jersey', 'California'],
        ],
        [
            'label' => 'Cities',
            'options' => ['Jersey City', 'Manhattan', 'Queens', 'Greenville'],
        ],
        [
            'label' => 'Areas',
            'options' => ['Bayonne', 'West Side', 'Upper East Side', 'West Village'],
        ],
        [
            'label' => 'Default',
            'options' => ['Newest', 'Price: Low to High', 'Price: High to Low', 'Featured'],
        ],
    ];
@endphp

<section class="bg-[#f3f5f7] py-8 sm:py-10">
    <div class="section-shell">
        <div class="properties-listing-layout">
            <aside class="space-y-5 properties-sidebar">
                @foreach ($sidebarSections as $section)
                    <div class="rounded bg-[#ececec] p-5">
                        <button type="button" class="properties-accordion-toggle flex w-full items-center justify-between gap-4 text-left" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                            <h3 class="text-[22px] font-semibold tracking-[-0.02em] text-[#1f2a44]">{{ $section['title'] }}</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="properties-accordion-icon h-5 w-5 shrink-0 text-slate-500 transition-transform {{ $loop->first ? 'rotate-180' : '' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div class="properties-accordion-panel {{ $loop->first ? '' : 'hidden' }}">
                        <ul class="mt-4 divide-y divide-slate-300/70 text-sm">
                            @foreach ($section['items'] as $item)
                                <li class="flex items-center justify-between py-3 text-slate-700">
                                    <span>{{ $item['name'] }}</span>
                                    <span class="text-slate-500">({{ $item['count'] }})</span>
                                </li>
                            @endforeach
                        </ul>
                        </div>
                    </div>
                @endforeach
            </aside>

            <div>
                <div data-properties-toolbar class="mb-5 flex items-center justify-between rounded-md bg-[#f0f2f4] px-3 py-3 text-sm text-slate-600 sm:px-5">
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                        @foreach ($topDropdowns as $dropdown)
                            <details class="group relative properties-filter-dropdown">
                                <summary class="inline-flex cursor-pointer list-none items-center gap-1.5 font-medium text-slate-600 transition hover:text-slate-900">
                                    <span>{{ $dropdown['label'] }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.12l3.71-3.9a.75.75 0 111.08 1.04l-4.25 4.47a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </summary>

                                <div class="absolute left-0 top-full z-20 mt-2 min-w-[180px] rounded-md border border-slate-200 bg-white p-1 shadow-lg">
                                    @foreach ($dropdown['options'] as $option)
                                        <button type="button" class="block w-full rounded px-3 py-2 text-left text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                                            {{ $option }}
                                        </button>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <div id="properties-view-controls" class="flex items-center gap-2 text-slate-500">
                        <button type="button" data-properties-view="grid" aria-label="Grid view" class="properties-view-btn is-active inline-flex h-8 w-8 items-center justify-center rounded border border-slate-200 bg-white text-[#1e73be]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 3h5v5H3V3zm0 9h5v5H3v-5zm9-9h5v5h-5V3zm0 9h5v5h-5v-5z" />
                            </svg>
                        </button>
                        <button type="button" data-properties-view="list" aria-label="List view" class="properties-view-btn inline-flex h-8 w-8 items-center justify-center rounded border border-slate-200 bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 5h14a1 1 0 100-2H3a1 1 0 000 2zm14 4H3a1 1 0 100 2h14a1 1 0 100-2zm0 6H3a1 1 0 100 2h14a1 1 0 100-2z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="properties-cards-grid" data-properties-cards>
                @foreach ($listingProperties as $property)
                    <article class="property-listing-card overflow-hidden rounded border border-slate-200 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04)]">
                        <div class="relative property-listing-media">
                            <a href="{{ route('property-detail') }}" class="block">
                                <img src="{{ $property['image'] }}" alt="{{ $property['title'] }}" class="h-56 w-full object-cover">
                            </a>
                            <div class="absolute left-3 top-3 rounded bg-emerald-500 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">Featured</div>
                            <div class="absolute right-3 top-3 flex gap-1">
                                <span class="rounded bg-[#1e73be] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">{{ $property['badgeOne'] }}</span>
                                <span class="rounded bg-[#2f7ecc] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">{{ $property['badgeTwo'] }}</span>
                            </div>
                            <div class="absolute bottom-3 left-3 flex items-center gap-1.5 text-xs text-white drop-shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 8.05a7 7 0 119.9 9.9L10 22.9l-4.95-4.95a7 7 0 010-9.9zm7.07 2.83a2.5 2.5 0 11-3.54 3.54 2.5 2.5 0 013.54-3.54z" clip-rule="evenodd" transform="translate(0 -4)" />
                                </svg>
                                <span>{{ $property['location'] }}</span>
                            </div>
                            <div class="absolute bottom-3 right-3 flex items-center gap-2 text-xs text-white">
                                <span class="inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6a2 2 0 012-2h7a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm13 3h1a2 2 0 012 2v7a2 2 0 01-2 2h-1v-2h1v-7h-1V9z"/></svg>
                                    {{ $property['metaMedia'] }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 property-listing-content">
                            <h3 class="text-[24px] font-semibold leading-tight tracking-[-0.02em] text-[#1f2a44]"><a href="{{ route('property-detail') }}" class="hover:text-[#1e73be]">{{ $property['title'] }}</a></h3>
                            <p class="mt-1 text-[24px] font-bold leading-none text-[#1e73be]">{{ $property['priceText'] }}</p>
                            <p class="mt-3 text-[15px] leading-7 text-slate-600">{{ $property['description'] }}</p>

                            <div class="mt-4 flex items-center gap-4 border-b border-slate-200 pb-3 text-sm text-slate-500">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 7a3 3 0 013-3h10a3 3 0 013 3v9h1a1 1 0 110 2H3a1 1 0 110-2h1V7zm2 9h12V7a1 1 0 00-1-1H7a1 1 0 00-1 1v9z"/></svg>
                                    {{ $property['metaBeds'] }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7 4a1 1 0 011 1v1h8V5a1 1 0 112 0v1h1a2 2 0 012 2v9a3 3 0 01-3 3H6a3 3 0 01-3-3V8a2 2 0 012-2h1V5a1 1 0 011-1zm12 6H5v7a1 1 0 001 1h12a1 1 0 001-1v-7z"/></svg>
                                    {{ $property['metaBaths'] }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm13 1a1 1 0 10-2 0v6a1 1 0 001 1h5a1 1 0 100-2h-4v-5z"/></svg>
                                    {{ $property['metaSize'] }}
                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $property['agentAvatar'] }}" alt="{{ $property['agent'] }}" class="h-7 w-7 rounded-full object-cover">
                                    <span class="text-sm font-medium text-slate-700">{{ $property['agent'] }}</span>
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
                @endforeach

                </div>

                <nav class="mt-6 flex items-center justify-center gap-2" aria-label="Pagination">
                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-300 bg-white text-slate-500 hover:bg-slate-100" aria-label="Previous page">&lsaquo;</button>
                    <button type="button" class="inline-flex h-8 min-w-8 items-center justify-center rounded border border-[#1e73be] bg-[#1e73be] px-2 text-sm font-semibold text-white">1</button>
                    <button type="button" class="inline-flex h-8 min-w-8 items-center justify-center rounded border border-slate-300 bg-white px-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">2</button>
                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-300 bg-white text-slate-500 hover:bg-slate-100" aria-label="Next page">&rsaquo;</button>
                </nav>
            </div>
        </div>
    </div>
</section>

