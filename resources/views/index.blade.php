<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'RealEstate') }} | Home</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f3f7fc] text-slate-700">
        <header class="relative isolate overflow-visible bg-[#0f355e]">
            <img src="{{ asset('images/hero-home.webp') }}" alt="Hero home" class="absolute inset-0 h-full w-full object-cover" onerror="this.style.display='none'">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(12,46,78,0.74)_0%,rgba(11,47,79,0.84)_52%,rgba(9,39,66,0.9)_100%)]"></div>

            <div class="relative z-20 border-b border-white/20 bg-[#0c355b]/35">
                <div class="section-shell hidden h-10 items-center justify-between text-[11px] font-semibold uppercase tracking-[0.05em] text-white/90 lg:flex">
                    <div class="flex items-stretch border-l border-r border-white/18">
                        <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">f</span>
                        <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">x</span>
                        <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">in</span>
                        <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">p</span>
                        <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">o</span>
                        <span class="inline-flex h-10 w-10 items-center justify-center">v</span>
                    </div>
                    <div class="flex items-center gap-8 text-[12px] font-semibold normal-case tracking-normal">
                        <span class="inline-flex items-center gap-1.5">❤ Favorites</span>
                        <span class="inline-flex items-center gap-1.5">✉ contact@mail.com</span>
                        <span class="inline-flex items-center gap-1.5">☎ +1 408 167 1234</span>
                    </div>
                </div>
            </div>

            <div class="section-shell relative z-20 py-4 sm:py-6">
                <nav class="flex items-center justify-between rounded-xl border border-white/20 bg-white/8 px-4 py-3.5 text-white backdrop-blur-sm sm:px-5 lg:px-7">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-md border border-white/30 bg-gradient-to-br from-[#2a8def] to-[#0f2f54] text-sm font-extrabold text-white">RS</span>
                        <span class="text-[1.45rem] font-extrabold tracking-[0.01em] sm:text-[1.6rem]">RealScape</span>
                    </a>

                    <button id="menu-toggle" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/30 text-white lg:hidden" aria-label="Toggle menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div id="menu-panel" class="hidden items-center gap-7 lg:flex">
                        <a href="{{ route('home') }}" class="text-[15px] font-bold text-white/95 hover:text-white">Home</a>
                        <a href="{{ route('properties') }}" class="text-[15px] font-semibold text-white/95 hover:text-white">Properties</a>
                        <a href="#category" class="text-[15px] font-semibold text-white/95 hover:text-white">Demos</a>
                        <a href="#latest" class="text-[15px] font-semibold text-white/95 hover:text-white">Lists</a>
                        <a href="#agents" class="text-[15px] font-semibold text-white/95 hover:text-white">Property</a>
                        <a href="#testimonials" class="text-[15px] font-semibold text-white/95 hover:text-white">Pages</a>
                        <span class="hidden items-center gap-2 border-l border-white/20 pl-6 text-[14px] font-semibold xl:inline-flex">☎ 800-555-6789</span>
                        <a href="#contact" class="rounded-lg bg-[#0f6fcb] px-5 py-2.5 text-[14px] font-bold text-white shadow-[0_10px_24px_rgba(15,111,203,0.35)] transition hover:bg-[#0c5eac]">Add Listing</a>
                    </div>
                </nav>

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
                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=200&q=80" alt="Jersey City" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Jersey City</h3>
                                <p class="mt-1 text-sm text-slate-500">25 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=200&q=80" alt="The Heights" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">The Heights</h3>
                                <p class="mt-1 text-sm text-slate-500">3 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=200&q=80" alt="New Jersey State" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">New Jersey State</h3>
                                <p class="mt-1 text-sm text-slate-500">25 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=200&q=80" alt="New York State" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">New York State</h3>
                                <p class="mt-1 text-sm text-slate-500">16 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=200&q=80" alt="New York" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">New York</h3>
                                <p class="mt-1 text-sm text-slate-500">17 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?auto=format&fit=crop&w=200&q=80" alt="Bayonne" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Bayonne</h3>
                                <p class="mt-1 text-sm text-slate-500">5 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1479839672679-a46483c0e7c8?auto=format&fit=crop&w=200&q=80" alt="Greenville" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Greenville</h3>
                                <p class="mt-1 text-sm text-slate-500">17 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=200&q=80" alt="Manhattan" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Manhattan</h3>
                                <p class="mt-1 text-sm text-slate-500">8 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=200&q=80" alt="Upper East Side" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Upper East Side</h3>
                                <p class="mt-1 text-sm text-slate-500">3 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=200&q=80" alt="Queens" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Queens</h3>
                                <p class="mt-1 text-sm text-slate-500">4 listings</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=200&q=80" alt="West Side" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">West Side</h3>
                                <p class="mt-1 text-sm text-slate-500">1 listing</p>
                            </div>
                        </article>

                        <article class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=200&q=80" alt="West Village" class="h-20 w-20 rounded-sm object-cover shadow-sm">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">West Village</h3>
                                <p class="mt-1 text-sm text-slate-500">1 listing</p>
                            </div>
                        </article>
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
                    <span class="rounded-md bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">For Sale</span>
                    <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Villas</span>
                    <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Apartments</span>
                    <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Houses</span>
                    <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Condos</span>
                    <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Retail</span>
                </div>

                <div class="mx-auto mt-6 grid max-w-6xl gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <img src="https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=900&q=80" alt="Luxury House in Greenville" class="h-52 w-full object-cover">
                        <div class="p-4">
                            <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
                                <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                                <div class="flex gap-1">
                                    <span class="rounded bg-blue-600 px-2 py-1">Sale</span>
                                    <span class="rounded bg-blue-500 px-2 py-1">Video</span>
                                </div>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Luxury House in Greenville</h3>
                            <p class="mt-1 text-sm font-semibold text-blue-600">$660,000</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">This property is neatly assigned and is just high on a hilltop overlooking the Morning Rose Valley.</p>
                            <p class="mt-3 text-sm text-slate-400">Greenville, New York</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80" alt="Modern Condo for Sale" class="h-52 w-full object-cover">
                        <div class="p-4">
                            <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
                                <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                                <div class="flex gap-1">
                                    <span class="rounded bg-blue-600 px-2 py-1">New</span>
                                    <span class="rounded bg-blue-500 px-2 py-1">Home</span>
                                </div>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Modern Condo for Sale</h3>
                            <p class="mt-1 text-sm font-semibold text-blue-600">$150,000</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Beautiful, updated, grand loft condo apartment in the beautiful city of New York.</p>
                            <p class="mt-3 text-sm text-slate-400">Manhattan, New York</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <img src="https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=900&q=80" alt="Apartment with Subunits" class="h-52 w-full object-cover">
                        <div class="p-4">
                            <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
                                <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                                <div class="flex gap-1">
                                    <span class="rounded bg-blue-600 px-2 py-1">Rent</span>
                                    <span class="rounded bg-blue-500 px-2 py-1">Video</span>
                                </div>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Apartment with Subunits</h3>
                            <p class="mt-1 text-sm font-semibold text-blue-600">$999 / month</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Streamlined international corporations are an additional option to increase sales in the area.</p>
                            <p class="mt-3 text-sm text-slate-400">Bayonne, Jersey City</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80" alt="Three Room Apartment" class="h-52 w-full object-cover">
                        <div class="p-4">
                            <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
                                <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                                <div class="flex gap-1">
                                    <span class="rounded bg-blue-600 px-2 py-1">Invest</span>
                                    <span class="rounded bg-blue-500 px-2 py-1">Rent</span>
                                </div>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Three Room Apartment</h3>
                            <p class="mt-1 text-sm font-semibold text-blue-600">$550,000</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Beautiful, huge 1 family home in heart of Westbury, nicely placed with modern details.</p>
                            <p class="mt-3 text-sm text-slate-400">Westbury, New York</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <img src="https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=900&q=80" alt="Suburb Home for Sale" class="h-52 w-full object-cover">
                        <div class="p-4">
                            <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
                                <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                                <div class="flex gap-1">
                                    <span class="rounded bg-blue-600 px-2 py-1">Invest</span>
                                    <span class="rounded bg-blue-500 px-2 py-1">Video</span>
                                </div>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Suburb Home for Sale</h3>
                            <p class="mt-1 text-sm font-semibold text-blue-600">$1,000,000</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Beautiful, 1900ft, newly built 3000 sq ft building that could be used as a great investment.</p>
                            <p class="mt-3 text-sm text-slate-400">Long Island, New York</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=900&q=80" alt="Gorgeous Home for Sale in Jersey" class="h-52 w-full object-cover">
                        <div class="p-4">
                            <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
                                <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                                <div class="flex gap-1">
                                    <span class="rounded bg-blue-600 px-2 py-1">Sale</span>
                                    <span class="rounded bg-blue-500 px-2 py-1">Video</span>
                                </div>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Gorgeous Home for Sale in Jersey</h3>
                            <p class="mt-1 text-sm font-semibold text-blue-600">$770,000</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">This property is neatly assigned and sits high on a hilltop overlooking the Morning Rose Valley.</p>
                            <p class="mt-3 text-sm text-slate-400">Jersey City, New Jersey</p>
                        </div>
                    </article>
                </div>

                <div class="mt-6 text-center">
                    <button class="rounded-md bg-[#0f6fcb] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#0c5eac]">Load More Listings</button>
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
                    <article class="relative min-h-72 overflow-hidden rounded-sm bg-slate-200 shadow-sm lg:col-span-2 lg:row-span-1">
                        <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80" alt="Apartments" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-black/18"></div>
                        <div class="absolute inset-0 p-4 text-white">
                            <h3 class="text-lg font-semibold">Apartments</h3>
                            <p class="absolute bottom-4 left-4 text-sm font-medium">17 listings</p>
                        </div>
                    </article>

                    <article class="relative min-h-72 overflow-hidden rounded-sm bg-slate-200 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=900&q=80" alt="Condos" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-black/18"></div>
                        <div class="absolute inset-0 p-4 text-white">
                            <h3 class="text-lg font-semibold">Condos</h3>
                            <p class="absolute bottom-4 left-4 text-sm font-medium">8 listings</p>
                        </div>
                    </article>

                    <article class="relative min-h-72 overflow-hidden rounded-sm bg-slate-200 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=900&q=80" alt="Duplexes" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-black/18"></div>
                        <div class="absolute inset-0 p-4 text-white">
                            <h3 class="text-lg font-semibold">Duplexes</h3>
                            <p class="absolute bottom-4 left-4 text-sm font-medium">0 listings</p>
                        </div>
                    </article>

                    <article class="relative min-h-72 overflow-hidden rounded-sm bg-slate-200 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?auto=format&fit=crop&w=900&q=80" alt="Houses" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-black/18"></div>
                        <div class="absolute inset-0 p-4 text-white">
                            <h3 class="text-lg font-semibold">Houses</h3>
                            <p class="absolute bottom-4 left-4 text-sm font-medium">5 listings</p>
                        </div>
                    </article>

                    <article class="relative min-h-72 overflow-hidden rounded-sm bg-slate-200 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80" alt="Offices" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-black/18"></div>
                        <div class="absolute inset-0 p-4 text-white">
                            <h3 class="text-lg font-semibold">Offices</h3>
                            <p class="absolute bottom-4 left-4 text-sm font-medium">2 listings</p>
                        </div>
                    </article>

                    <article class="relative min-h-72 overflow-hidden rounded-sm bg-slate-200 shadow-sm lg:col-span-2">
                        <img src="https://images.unsplash.com/photo-1617104678098-de229db51175?auto=format&fit=crop&w=1200&q=80" alt="Villas" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-black/18"></div>
                        <div class="absolute inset-0 p-4 text-white">
                            <h3 class="text-lg font-semibold">Villas</h3>
                            <p class="absolute bottom-4 left-4 text-sm font-medium">4 listings</p>
                        </div>
                    </article>
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

                <button class="absolute left-0 top-1/2 hidden -translate-x-5 rounded bg-[#e8edf7] p-2 text-[#2f6dd7] shadow-sm md:block" aria-label="Previous slide">&larr;</button>
                <button class="absolute right-0 top-1/2 hidden translate-x-5 rounded bg-[#e8edf7] p-2 text-[#2f6dd7] shadow-sm md:block" aria-label="Next slide">&rarr;</button>

                <div class="mx-auto mt-8 grid max-w-6xl gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=900&q=80" alt="Apartment with Subunits" class="h-56 w-full object-cover">
                            <div class="absolute left-3 top-3 flex items-center gap-1 text-[10px] font-semibold uppercase text-white">
                                <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
                            </div>
                            <div class="absolute right-3 top-3 flex items-center gap-1 text-[10px] font-semibold uppercase text-white">
                                <span class="rounded bg-blue-600 px-2 py-1">Rentals</span>
                                <span class="rounded bg-blue-500 px-2 py-1">Active</span>
                            </div>
                            <p class="absolute bottom-3 left-3 text-xs font-semibold text-white">Greenville, Jersey City</p>
                            <p class="absolute bottom-3 right-3 text-xs font-semibold text-white">7</p>
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Apartment with Subunits</h3>
                            <p class="mt-1 text-lg font-semibold text-blue-600">$999 / month</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Sesame Street international co-productions are educational children's television series.</p>
                            <div class="mt-4 flex items-center gap-4 border-b border-slate-100 pb-3 text-xs text-slate-500">
                                <span>5</span>
                                <span>2</span>
                                <span>1,900 ft²</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-700">Lora Smith</p>
                                <div class="flex gap-1 text-slate-400">
                                    <span class="rounded border border-slate-200 px-2 py-1">+</span>
                                    <span class="rounded border border-slate-200 px-2 py-1">♡</span>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80" alt="New York Studio Apartment" class="h-56 w-full object-cover">
                            <div class="absolute right-3 top-3 flex items-center gap-1 text-[10px] font-semibold uppercase text-white">
                                <span class="rounded bg-blue-600 px-2 py-1">Rentals</span>
                                <span class="rounded bg-blue-500 px-2 py-1">Active</span>
                            </div>
                            <p class="absolute bottom-3 left-3 text-xs font-semibold text-white">Manhattan, New York</p>
                            <p class="absolute bottom-3 right-3 text-xs font-semibold text-white">6</p>
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">New York Studio Apartment</h3>
                            <p class="mt-1 text-lg font-semibold text-blue-600">$2,000 / year</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">This property is mostly wooded and sits high on a hilltop overlooking the Mohawk River Valley.</p>
                            <div class="mt-4 flex items-center gap-4 border-b border-slate-100 pb-3 text-xs text-slate-500">
                                <span>1</span>
                                <span>1</span>
                                <span>120 ft²</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-700">Green Reality</p>
                                <div class="flex gap-1 text-slate-400">
                                    <span class="rounded border border-slate-200 px-2 py-1">+</span>
                                    <span class="rounded border border-slate-200 px-2 py-1">♡</span>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80" alt="Luxury Home in Manhattan" class="h-56 w-full object-cover">
                            <div class="absolute right-3 top-3 flex items-center gap-1 text-[10px] font-semibold uppercase text-white">
                                <span class="rounded bg-blue-600 px-2 py-1">Rentals</span>
                                <span class="rounded bg-blue-500 px-2 py-1">Open House</span>
                            </div>
                            <p class="absolute bottom-3 left-3 text-xs font-semibold text-white">Manhattan, New York</p>
                            <p class="absolute bottom-3 right-3 text-xs font-semibold text-white">7</p>
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Luxury Home in Manhattan</h3>
                            <p class="mt-1 text-lg font-semibold text-blue-600">$450 / month</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Just steps away from QM2 express bus to Manhattan and local buses; only minutes from the L train.</p>
                            <div class="mt-4 flex items-center gap-4 border-b border-slate-100 pb-3 text-xs text-slate-500">
                                <span>2</span>
                                <span>3</span>
                                <span>120 ft²</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-700">Green Reality</p>
                                <div class="flex gap-1 text-slate-400">
                                    <span class="rounded border border-slate-200 px-2 py-1">+</span>
                                    <span class="rounded border border-slate-200 px-2 py-1">♡</span>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="mt-6 flex justify-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#2f6dd7]"></span>
                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                </div>
            </div>
        </section>

        <section class="bg-[#eef5fc] py-12 sm:py-16">
            <div class="section-shell">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="section-title">Featured Properties</h2>
                    <p class="section-copy mx-auto mt-4 max-w-3xl">
                        Here are two listings displayed with the featured property shortcode, which you
                        can use when you have some special properties to present.
                    </p>
                </div>

                <div class="mx-auto mt-8 grid max-w-6xl gap-6 lg:grid-cols-2">
                    <article class="featured-slider overflow-hidden rounded-sm bg-white shadow-sm ring-1 ring-slate-200"
                        data-images="https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=900&q=80|https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=900&q=80|https://images.unsplash.com/photo-1600047509782-20d39509f26d?auto=format&fit=crop&w=900&q=80">
                        <div class="grid md:grid-cols-2">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=900&q=80" alt="Villa on Washington Avenue" class="featured-slide-image h-full min-h-72 w-full object-cover transition-opacity duration-300">
                                <button type="button" class="featured-slide-prev absolute left-3 top-1/2 -translate-y-1/2 rounded bg-black/25 px-2 py-1 text-white">&lt;</button>
                                <button type="button" class="featured-slide-next absolute right-3 top-1/2 -translate-y-1/2 rounded bg-black/25 px-2 py-1 text-white">&gt;</button>
                                <img src="https://images.unsplash.com/photo-1545167622-3a6ac756afa4?auto=format&fit=crop&w=120&q=80" alt="Agent" class="absolute bottom-3 left-3 h-12 w-12 rounded-full border-2 border-white object-cover shadow-sm">
                            </div>
                            <div class="flex flex-col p-6">
                                <h3 class="text-xl font-semibold tracking-[-0.012em] text-[#1f2a44] sm:text-2xl">Villa on Washington Avenue</h3>
                                <p class="mt-3 text-3xl font-semibold text-blue-600">$150,000</p>
                                <p class="mt-4 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">Beautiful, updated, ground level co-op apartment in the desirable Bay area with premium features.</p>
                                <div class="mt-5 flex gap-2">
                                    <span class="rounded border border-slate-200 px-3 py-2 text-slate-400">⇄</span>
                                    <span class="rounded border border-slate-200 px-3 py-2 text-blue-600">♥</span>
                                </div>
                                <div class="mt-auto border-t border-slate-200 pt-5 text-sm text-slate-500">
                                    <div class="flex items-center gap-5">
                                        <span>5</span>
                                        <span>3</span>
                                        <span>250 ft²</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="featured-slider overflow-hidden rounded-sm bg-white shadow-sm ring-1 ring-slate-200"
                        data-images="https://images.unsplash.com/photo-1600047509782-20d39509f26d?auto=format&fit=crop&w=900&q=80|https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=900&q=80|https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80">
                        <div class="grid md:grid-cols-2">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1600047509782-20d39509f26d?auto=format&fit=crop&w=900&q=80" alt="Luxury villa in Rego Park" class="featured-slide-image h-full min-h-72 w-full object-cover transition-opacity duration-300">
                                <button type="button" class="featured-slide-prev absolute left-3 top-1/2 -translate-y-1/2 rounded bg-black/25 px-2 py-1 text-white">&lt;</button>
                                <button type="button" class="featured-slide-next absolute right-3 top-1/2 -translate-y-1/2 rounded bg-black/25 px-2 py-1 text-white">&gt;</button>
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80" alt="Agent" class="absolute bottom-3 left-3 h-12 w-12 rounded-full border-2 border-white object-cover shadow-sm">
                            </div>
                            <div class="flex flex-col p-6">
                                <h3 class="text-xl font-semibold tracking-[-0.012em] text-[#1f2a44] sm:text-2xl">Luxury villa in Rego Park</h3>
                                <p class="mt-3 text-3xl font-semibold text-blue-600">$420,000</p>
                                <p class="mt-4 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">Just steps away from QM2 express bus to Manhattan and local buses; close to parks and schools.</p>
                                <div class="mt-5 flex gap-2">
                                    <span class="rounded border border-slate-200 px-3 py-2 text-slate-400">⇄</span>
                                    <span class="rounded border border-slate-200 px-3 py-2 text-slate-400">♡</span>
                                </div>
                                <div class="mt-auto border-t border-slate-200 pt-5 text-sm text-slate-500">
                                    <div class="flex items-center gap-5">
                                        <span>3</span>
                                        <span>2</span>
                                        <span>150 ft²</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="w-full bg-white py-0">
            <div class="grid w-full gap-0 lg:grid-cols-2">
                <article class="relative min-h-[340px] overflow-hidden lg:min-h-[460px]">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1400&q=80" alt="Gorgeous Home for Sale in Jersey" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/35 via-black/20 to-transparent"></div>

                    <div class="absolute left-1/2 top-1/2 w-[86%] max-w-[460px] -translate-x-1/2 -translate-y-1/2 bg-white/95 p-6 shadow-xl backdrop-blur-sm sm:p-8">
                        <p class="text-sm font-semibold text-blue-600 sm:text-base">$770,000</p>
                        <h3 class="mt-3 text-2xl font-bold leading-tight tracking-[-0.015em] text-[#1f2a44] sm:text-3xl">Gorgeous Home for Sale in Jersey</h3>
                        <p class="mt-3 text-xs text-slate-500 sm:text-sm">5 BD · 6 BA · 190 ft²</p>
                        <p class="mt-4 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                            This property is mostly wooded and sits high on a hilltop overlooking the Mohawk
                            River Valley. Located right in the heart of upstate New York.
                        </p>
                        <a href="#" class="mt-5 inline-flex items-center text-xs font-semibold uppercase tracking-[0.08em] text-slate-800 hover:text-blue-600 sm:text-sm">discover more <span class="ml-2">&rsaquo;</span></a>
                    </div>
                </article>

                <div class="grid min-h-[340px] grid-cols-2 grid-rows-2 gap-[2px] bg-white lg:min-h-[460px]">
                    <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1000&q=80" alt="Interior gallery image" class="h-full w-full object-cover">
                    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1000&q=80" alt="Interior gallery image" class="h-full w-full object-cover">
                    <img src="https://images.unsplash.com/photo-1617104678098-de229db51175?auto=format&fit=crop&w=1000&q=80" alt="Interior gallery image" class="h-full w-full object-cover">
                    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1000&q=80" alt="Interior gallery image" class="h-full w-full object-cover">
                </div>
            </div>
        </section>

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
                    <article class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="relative aspect-[4/3] overflow-hidden bg-[#f1f3f8]">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=900&q=80" alt="Lily Bicharm" class="h-full w-full object-cover object-center" loading="lazy">
                            <span class="absolute bottom-3 left-3 rounded bg-emerald-500 px-3 py-1 text-xs font-semibold text-white">4 listings</span>
                        </div>
                        <div class="p-5">
                            <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Lily Bicharm</h3>
                            <p class="mt-1 text-sm text-[#6a7892] sm:text-base">Realtor</p>
                            <p class="mt-4 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                                Lily Bicharm is a dedicated and professional real estate agent who specializes in providing premium support.
                            </p>
                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                                <div class="flex items-center gap-2 text-slate-500">
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Facebook"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 9H16V6h-2.5C11.6 6 10 7.6 10 9.5V12H8v3h2v6h3v-6h2.3l.7-3H13v-2.2c0-.5.2-.8.5-.8z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="X"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H22l-6.8 7.8L23 21h-6.2l-4.9-6.4L6.3 21H3.1l7.3-8.3L1 3h6.3l4.4 5.9L18.9 3z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="LinkedIn"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5A1.9 1.9 0 1 0 6.9 4.7a1.9 1.9 0 0 0 0 3.8zM5.2 10h3.4v9.3H5.2V10zm5.4 0H14v1.3h.1c.5-.9 1.6-1.6 3.2-1.6 3.5 0 4.1 2.3 4.1 5.3v4.3H18v-3.8c0-.9 0-2.1-1.3-2.1s-1.5 1-1.5 2v4H12V10z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Pinterest"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 0 0-3.3 17.4c0-.6 0-1.6.2-2.3l1.2-5.1s-.3-.6-.3-1.5c0-1.4.8-2.4 1.8-2.4.8 0 1.2.6 1.2 1.4 0 .8-.5 2.1-.8 3.2-.2.9.5 1.7 1.4 1.7 1.7 0 2.9-1.8 2.9-4.3 0-2.2-1.6-3.8-3.9-3.8-2.7 0-4.2 2-4.2 4.1 0 .8.3 1.7.7 2.2.1.2.1.2.1.4l-.3 1c-.1.2-.2.3-.5.2-1.7-.8-2.8-3.1-2.8-5 0-4.1 3-7.8 8.6-7.8 4.5 0 8 3.2 8 7.5 0 4.5-2.8 8.1-6.7 8.1-1.3 0-2.6-.7-3-1.5l-.8 3.2c-.3 1.1-1 2.5-1.4 3.3 1 .3 2 .5 3.1.5a9 9 0 1 0 0-18z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Instagram"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 8.2A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4zM18.4 6.9a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/><path d="M12 2.8c3 0 3.3 0 4.4.1 1 .1 1.5.2 1.8.4.5.2.8.4 1.1.7.3.3.5.6.7 1.1.2.3.3.9.4 1.8.1 1.1.1 1.4.1 4.4s0 3.3-.1 4.4c-.1 1-.2 1.5-.4 1.8-.2.5-.4.8-.7 1.1-.3.3-.6.5-1.1.7-.3.2-.9.3-1.8.4-1.1.1-1.4.1-4.4.1s-3.3 0-4.4-.1c-1-.1-1.5-.2-1.8-.4a3 3 0 0 1-1.1-.7 3 3 0 0 1-.7-1.1c-.2-.3-.3-.9-.4-1.8-.1-1.1-.1-1.4-.1-4.4s0-3.3.1-4.4c.1-1 .2-1.5.4-1.8.2-.5.4-.8.7-1.1.3-.3.6-.5 1.1-.7.3-.2.9-.3 1.8-.4 1.1-.1 1.4-.1 4.4-.1zm0-1.8c-3.1 0-3.5 0-4.6.1-1.1.1-1.9.2-2.6.5a4.8 4.8 0 0 0-1.7 1.1A4.8 4.8 0 0 0 2 4.4c-.3.7-.4 1.5-.5 2.6C1.4 8 1.4 8.4 1.4 11.5S1.4 15 1.5 16c.1 1.1.2 1.9.5 2.6a4.8 4.8 0 0 0 1.1 1.7 4.8 4.8 0 0 0 1.7 1.1c.7.3 1.5.4 2.6.5 1.1.1 1.4.1 4.6.1 3.1 0 3.5 0 4.6-.1 1.1-.1 1.9-.2 2.6-.5a4.8 4.8 0 0 0 1.7-1.1 4.8 4.8 0 0 0 1.1-1.7c.3-.7.4-1.5.5-2.6.1-1.1.1-1.4.1-4.6s0-3.5-.1-4.6c-.1-1.1-.2-1.9-.5-2.6a4.8 4.8 0 0 0-1.1-1.7 4.8 4.8 0 0 0-1.7-1.1c-.7-.3-1.5-.4-2.6-.5C15.5 1 15.2 1 12 1z"/></svg></a>
                                </div>
                                <div class="flex items-center gap-2 text-slate-500">
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Call"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.3 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C11.9 21 3 12.1 3 1.9 3 1.3 3.4.9 4 .9h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.3 1l-2.2 2.3z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Email"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg></a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="relative aspect-[4/3] overflow-hidden bg-[#f1f3f8]">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=900&q=80" alt="Samuel Diesel" class="h-full w-full object-cover object-center" loading="lazy">
                            <span class="absolute bottom-3 left-3 rounded bg-emerald-500 px-3 py-1 text-xs font-semibold text-white">4 listings</span>
                        </div>
                        <div class="p-5">
                            <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Samuel Diesel</h3>
                            <p class="mt-1 text-sm text-[#6a7892] sm:text-base">Commercial Broker</p>
                            <p class="mt-4 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                                With a genuine passion for helping clients find their dream homes, Samuel Diesel brings a strategic approach.
                            </p>
                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                                <div class="flex items-center gap-2 text-slate-500">
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Facebook"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 9H16V6h-2.5C11.6 6 10 7.6 10 9.5V12H8v3h2v6h3v-6h2.3l.7-3H13v-2.2c0-.5.2-.8.5-.8z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="X"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H22l-6.8 7.8L23 21h-6.2l-4.9-6.4L6.3 21H3.1l7.3-8.3L1 3h6.3l4.4 5.9L18.9 3z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="LinkedIn"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5A1.9 1.9 0 1 0 6.9 4.7a1.9 1.9 0 0 0 0 3.8zM5.2 10h3.4v9.3H5.2V10zm5.4 0H14v1.3h.1c.5-.9 1.6-1.6 3.2-1.6 3.5 0 4.1 2.3 4.1 5.3v4.3H18v-3.8c0-.9 0-2.1-1.3-2.1s-1.5 1-1.5 2v4H12V10z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Pinterest"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 0 0-3.3 17.4c0-.6 0-1.6.2-2.3l1.2-5.1s-.3-.6-.3-1.5c0-1.4.8-2.4 1.8-2.4.8 0 1.2.6 1.2 1.4 0 .8-.5 2.1-.8 3.2-.2.9.5 1.7 1.4 1.7 1.7 0 2.9-1.8 2.9-4.3 0-2.2-1.6-3.8-3.9-3.8-2.7 0-4.2 2-4.2 4.1 0 .8.3 1.7.7 2.2.1.2.1.2.1.4l-.3 1c-.1.2-.2.3-.5.2-1.7-.8-2.8-3.1-2.8-5 0-4.1 3-7.8 8.6-7.8 4.5 0 8 3.2 8 7.5 0 4.5-2.8 8.1-6.7 8.1-1.3 0-2.6-.7-3-1.5l-.8 3.2c-.3 1.1-1 2.5-1.4 3.3 1 .3 2 .5 3.1.5a9 9 0 1 0 0-18z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Instagram"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 8.2A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4zM18.4 6.9a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/><path d="M12 2.8c3 0 3.3 0 4.4.1 1 .1 1.5.2 1.8.4.5.2.8.4 1.1.7.3.3.5.6.7 1.1.2.3.3.9.4 1.8.1 1.1.1 1.4.1 4.4s0 3.3-.1 4.4c-.1 1-.2 1.5-.4 1.8-.2.5-.4.8-.7 1.1-.3.3-.6.5-1.1.7-.3.2-.9.3-1.8.4-1.1.1-1.4.1-4.4.1s-3.3 0-4.4-.1c-1-.1-1.5-.2-1.8-.4a3 3 0 0 1-1.1-.7 3 3 0 0 1-.7-1.1c-.2-.3-.3-.9-.4-1.8-.1-1.1-.1-1.4-.1-4.4s0-3.3.1-4.4c.1-1 .2-1.5.4-1.8.2-.5.4-.8.7-1.1.3-.3.6-.5 1.1-.7.3-.2.9-.3 1.8-.4 1.1-.1 1.4-.1 4.4-.1zm0-1.8c-3.1 0-3.5 0-4.6.1-1.1.1-1.9.2-2.6.5a4.8 4.8 0 0 0-1.7 1.1A4.8 4.8 0 0 0 2 4.4c-.3.7-.4 1.5-.5 2.6C1.4 8 1.4 8.4 1.4 11.5S1.4 15 1.5 16c.1 1.1.2 1.9.5 2.6a4.8 4.8 0 0 0 1.1 1.7 4.8 4.8 0 0 0 1.7 1.1c.7.3 1.5.4 2.6.5 1.1.1 1.4.1 4.6.1 3.1 0 3.5 0 4.6-.1 1.1-.1 1.9-.2 2.6-.5a4.8 4.8 0 0 0 1.7-1.1 4.8 4.8 0 0 0 1.1-1.7c.3-.7.4-1.5.5-2.6.1-1.1.1-1.4.1-4.6s0-3.5-.1-4.6c-.1-1.1-.2-1.9-.5-2.6a4.8 4.8 0 0 0-1.1-1.7 4.8 4.8 0 0 0-1.7-1.1c-.7-.3-1.5-.4-2.6-.5C15.5 1 15.2 1 12 1z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="YouTube"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M23 12c0-2.2-.2-3.7-.6-4.7-.3-.8-1-1.5-1.8-1.8C19.6 5 12 5 12 5s-7.6 0-8.6.5c-.8.3-1.5 1-1.8 1.8C1.2 8.3 1 9.8 1 12c0 2.2.2 3.7.6 4.7.3.8 1 1.5 1.8 1.8.9.5 8.6.5 8.6.5s7.6 0 8.6-.5c.8-.3 1.5-1 1.8-1.8.4-1 .6-2.5.6-4.7zM10 15.5v-7l6 3.5-6 3.5z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Vimeo"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22 7.2c-.1 2.8-2 6.6-5.7 11.5-3.8 5.1-6.9 7.6-9.1 7.6-1.4 0-2.6-1.3-3.5-3.9-.6-2.3-1.2-4.6-1.8-6.9-.7-2.6-1.5-4-2.3-4-.2 0-1 .5-2.2 1.5L0 11.4c2-1.8 3.9-3.6 5.8-5.4 2.6-2.2 4.5-3.4 5.8-3.5 1.5-.2 2.4.9 2.8 3.2.4 2.5.7 4 .8 4.6.4 2 .8 3 1.4 3 .4 0 1.1-.7 2.1-2 .9-1.3 1.4-2.3 1.4-3 0-.9-.7-1.3-2-1.3.6-1.9 1.8-2.8 3.6-2.8 1.4 0 2.1 1 2 3z"/></svg></a>
                                </div>
                                <div class="flex items-center gap-2 text-slate-500">
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Call"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.3 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C11.9 21 3 12.1 3 1.9 3 1.3 3.4.9 4 .9h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.3 1l-2.2 2.3z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Email"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg></a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="relative aspect-[4/3] overflow-hidden bg-[#f1f3f8]">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=900&q=80" alt="Dennis Albo" class="h-full w-full object-cover object-center" loading="lazy">
                        </div>
                        <div class="p-5">
                            <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Dennis Albo</h3>
                            <p class="mt-1 text-sm text-[#6a7892] sm:text-base">Member ID: 987654321</p>
                            <p class="mt-4 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                                With a sharp understanding of market trends and a strategic approach to negotiation, Dennis delivers results.
                            </p>
                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                                <div class="flex items-center gap-2 text-slate-500">
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Facebook"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 9H16V6h-2.5C11.6 6 10 7.6 10 9.5V12H8v3h2v6h3v-6h2.3l.7-3H13v-2.2c0-.5.2-.8.5-.8z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="X"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H22l-6.8 7.8L23 21h-6.2l-4.9-6.4L6.3 21H3.1l7.3-8.3L1 3h6.3l4.4 5.9L18.9 3z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="LinkedIn"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5A1.9 1.9 0 1 0 6.9 4.7a1.9 1.9 0 0 0 0 3.8zM5.2 10h3.4v9.3H5.2V10zm5.4 0H14v1.3h.1c.5-.9 1.6-1.6 3.2-1.6 3.5 0 4.1 2.3 4.1 5.3v4.3H18v-3.8c0-.9 0-2.1-1.3-2.1s-1.5 1-1.5 2v4H12V10z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Pinterest"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 0 0-3.3 17.4c0-.6 0-1.6.2-2.3l1.2-5.1s-.3-.6-.3-1.5c0-1.4.8-2.4 1.8-2.4.8 0 1.2.6 1.2 1.4 0 .8-.5 2.1-.8 3.2-.2.9.5 1.7 1.4 1.7 1.7 0 2.9-1.8 2.9-4.3 0-2.2-1.6-3.8-3.9-3.8-2.7 0-4.2 2-4.2 4.1 0 .8.3 1.7.7 2.2.1.2.1.2.1.4l-.3 1c-.1.2-.2.3-.5.2-1.7-.8-2.8-3.1-2.8-5 0-4.1 3-7.8 8.6-7.8 4.5 0 8 3.2 8 7.5 0 4.5-2.8 8.1-6.7 8.1-1.3 0-2.6-.7-3-1.5l-.8 3.2c-.3 1.1-1 2.5-1.4 3.3 1 .3 2 .5 3.1.5a9 9 0 1 0 0-18z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Instagram"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 8.2A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4zM18.4 6.9a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/><path d="M12 2.8c3 0 3.3 0 4.4.1 1 .1 1.5.2 1.8.4.5.2.8.4 1.1.7.3.3.5.6.7 1.1.2.3.3.9.4 1.8.1 1.1.1 1.4.1 4.4s0 3.3-.1 4.4c-.1 1-.2 1.5-.4 1.8-.2.5-.4.8-.7 1.1-.3.3-.6.5-1.1.7-.3.2-.9.3-1.8.4-1.1.1-1.4.1-4.4.1s-3.3 0-4.4-.1c-1-.1-1.5-.2-1.8-.4a3 3 0 0 1-1.1-.7 3 3 0 0 1-.7-1.1c-.2-.3-.3-.9-.4-1.8-.1-1.1-.1-1.4-.1-4.4s0-3.3.1-4.4c.1-1 .2-1.5.4-1.8.2-.5.4-.8.7-1.1.3-.3.6-.5 1.1-.7.3-.2.9-.3 1.8-.4 1.1-.1 1.4-.1 4.4-.1zm0-1.8c-3.1 0-3.5 0-4.6.1-1.1.1-1.9.2-2.6.5a4.8 4.8 0 0 0-1.7 1.1A4.8 4.8 0 0 0 2 4.4c-.3.7-.4 1.5-.5 2.6C1.4 8 1.4 8.4 1.4 11.5S1.4 15 1.5 16c.1 1.1.2 1.9.5 2.6a4.8 4.8 0 0 0 1.1 1.7 4.8 4.8 0 0 0 1.7 1.1c.7.3 1.5.4 2.6.5 1.1.1 1.4.1 4.6.1 3.1 0 3.5 0 4.6-.1 1.1-.1 1.9-.2 2.6-.5a4.8 4.8 0 0 0 1.7-1.1 4.8 4.8 0 0 0 1.1-1.7c.3-.7.4-1.5.5-2.6.1-1.1.1-1.4.1-4.6s0-3.5-.1-4.6c-.1-1.1-.2-1.9-.5-2.6a4.8 4.8 0 0 0-1.1-1.7 4.8 4.8 0 0 0-1.7-1.1c-.7-.3-1.5-.4-2.6-.5C15.5 1 15.2 1 12 1z"/></svg></a>
                                </div>
                                <div class="flex items-center gap-2 text-slate-500">
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Call"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.3 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C11.9 21 3 12.1 3 1.9 3 1.3 3.4.9 4 .9h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.3 1l-2.2 2.3z"/></svg></a>
                                    <a href="#" class="rounded-md p-1.5 transition hover:bg-slate-100 hover:text-blue-600" aria-label="Email"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg></a>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

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
                    <article class="rounded bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?auto=format&fit=crop&w=120&q=80" alt="Dana Gilmore" class="h-10 w-10 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Dana Gilmore</h3>
                                <p class="text-sm text-slate-500">developer</p>
                            </div>
                        </div>
                        <p class="mt-5 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                            I reviewed and purchased a number of different WordPress themes before settling on Wp Residence.
                        </p>
                        <p class="mt-4 text-amber-400">★★★★★</p>
                    </article>

                    <article class="rounded bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80" alt="Susan Barkley" class="h-10 w-10 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Susan Barkley</h3>
                                <p class="text-sm text-slate-500">happy seller</p>
                            </div>
                        </div>
                        <p class="mt-5 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                            The WP Estate team did an outstanding job helping me buy and create my first real estate website.
                        </p>
                        <p class="mt-4 text-amber-400">★★★★★</p>
                    </article>

                    <article class="rounded bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1552058544-f2b08422138a?auto=format&fit=crop&w=120&q=80" alt="Lisa Simpson" class="h-10 w-10 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Lisa Simpson</h3>
                                <p class="text-sm text-slate-500">happy buyer</p>
                            </div>
                        </div>
                        <p class="mt-5 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                            We hired the WP Estate team as our buyer agent because they are the perfect team for real estate projects.
                        </p>
                        <p class="mt-4 text-amber-400">★★★★★</p>
                    </article>

                    <article class="rounded bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=120&q=80" alt="Sara Loreley" class="h-10 w-10 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Sara Loreley</h3>
                                <p class="text-sm text-slate-500">buyer</p>
                            </div>
                        </div>
                        <p class="mt-5 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                            I reviewed and purchased a number of different WordPress themes before settling on Wp Residence.
                        </p>
                        <p class="mt-4 text-amber-400">★★★★★</p>
                    </article>

                    <article class="rounded bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80" alt="Virginia Wolf" class="h-10 w-10 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Virginia Wolf</h3>
                                <p class="text-sm text-slate-500">happy seller</p>
                            </div>
                        </div>
                        <p class="mt-5 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                            The WP Estate team did an outstanding job helping me buy and create my first real estate website.
                        </p>
                        <p class="mt-4 text-amber-400">★★★★★</p>
                    </article>

                    <article class="rounded bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=120&q=80" alt="Jessica Fowley" class="h-10 w-10 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-semibold tracking-[-0.01em] text-[#1f2a44] sm:text-2xl">Jessica Fowley</h3>
                                <p class="text-sm text-slate-500">happy buyer</p>
                            </div>
                        </div>
                        <p class="mt-5 text-[15px] leading-7 text-[#5f6f8c] sm:text-base">
                            We hired the WP Estate team as our buyer agent because they are the perfect team for real estate projects.
                        </p>
                        <p class="mt-4 text-amber-400">★★★★★</p>
                    </article>
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

                    <form class="mt-6 space-y-3">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <input type="text" placeholder="Name" class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base">
                            <input type="text" placeholder="Mobile" class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base">
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <input type="email" placeholder="Email" class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base">
                            <input type="text" placeholder="City" class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base">
                        </div>

                        <textarea rows="4" placeholder="Message" class="w-full rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#2f6dd7] sm:text-base"></textarea>

                        <button type="submit" class="w-full rounded bg-[#0f6fcb] px-6 py-3 text-base font-semibold text-white transition hover:bg-[#0c5eac] sm:text-lg">
                            Send Email
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <footer class="w-full bg-[#041a30] py-12 text-white sm:py-14">
            <div class="section-shell">
                <div class="grid gap-8 border-b border-white/12 pb-8 lg:grid-cols-[1.2fr_0.9fr_1fr] lg:gap-12">
                    <div>
                        <h3 class="text-xl font-semibold tracking-[-0.01em] sm:text-2xl">Contact us</h3>
                        <ul class="mt-5 space-y-3 text-sm leading-6 text-slate-300 sm:text-[15px]">
                            <li>3755 Commercial St SE Salem, Corner with Sunny Boulevard, 3755 Commercial OR 97302</li>
                            <li>(305) 555-4446</li>
                            <li>(305) 555-4555</li>
                            <li>youremail@gmail.com</li>
                            <li>WP RESIDENCE</li>
                        </ul>

                        <div class="mt-7 flex flex-wrap gap-2.5">
                            <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="Facebook">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 9H16V6h-2.5C11.6 6 10 7.6 10 9.5V12H8v3h2v6h3v-6h2.3l.7-3H13v-2.2c0-.5.2-.8.5-.8z"/></svg>
                            </a>
                            <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="X">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H22l-6.8 7.8L23 21h-6.2l-4.9-6.4L6.3 21H3.1l7.3-8.3L1 3h6.3l4.4 5.9L18.9 3z"/></svg>
                            </a>
                            <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="LinkedIn">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5A1.9 1.9 0 1 0 6.9 4.7a1.9 1.9 0 0 0 0 3.8zM5.2 10h3.4v9.3H5.2V10zm5.4 0H14v1.3h.1c.5-.9 1.6-1.6 3.2-1.6 3.5 0 4.1 2.3 4.1 5.3v4.3H18v-3.8c0-.9 0-2.1-1.3-2.1s-1.5 1-1.5 2v4H12V10z"/></svg>
                            </a>
                            <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="Instagram">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 8.2A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4zM18.4 6.9a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/><path d="M12 2.8c3 0 3.3 0 4.4.1 1 .1 1.5.2 1.8.4.5.2.8.4 1.1.7.3.3.5.6.7 1.1.2.3.3.9.4 1.8.1 1.1.1 1.4.1 4.4s0 3.3-.1 4.4c-.1 1-.2 1.5-.4 1.8-.2.5-.4.8-.7 1.1-.3.3-.6.5-1.1.7-.3.2-.9.3-1.8.4-1.1.1-1.4.1-4.4.1s-3.3 0-4.4-.1c-1-.1-1.5-.2-1.8-.4a3 3 0 0 1-1.1-.7 3 3 0 0 1-.7-1.1c-.2-.3-.3-.9-.4-1.8-.1-1.1-.1-1.4-.1-4.4s0-3.3.1-4.4c.1-1 .2-1.5.4-1.8.2-.5.4-.8.7-1.1.3-.3.6-.5 1.1-.7.3-.2.9-.3 1.8-.4 1.1-.1 1.4-.1 4.4-.1zm0-1.8c-3.1 0-3.5 0-4.6.1-1.1.1-1.9.2-2.6.5a4.8 4.8 0 0 0-1.7 1.1A4.8 4.8 0 0 0 2 4.4c-.3.7-.4 1.5-.5 2.6C1.4 8 1.4 8.4 1.4 11.5S1.4 15 1.5 16c.1 1.1.2 1.9.5 2.6a4.8 4.8 0 0 0 1.1 1.7 4.8 4.8 0 0 0 1.7 1.1c.7.3 1.5.4 2.6.5 1.1.1 1.4.1 4.6.1 3.1 0 3.5 0 4.6-.1 1.1-.1 1.9-.2 2.6-.5a4.8 4.8 0 0 0 1.7-1.1 4.8 4.8 0 0 0 1.1-1.7c.3-.7.4-1.5.5-2.6.1-1.1.1-1.4.1-4.6s0-3.5-.1-4.6c-.1-1.1-.2-1.9-.5-2.6a4.8 4.8 0 0 0-1.1-1.7 4.8 4.8 0 0 0-1.7-1.1c-.7-.3-1.5-.4-2.6-.5C15.5 1 15.2 1 12 1z"/></svg>
                            </a>
                            <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="YouTube">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M23 12c0-2.2-.2-3.7-.6-4.7-.3-.8-1-1.5-1.8-1.8C19.6 5 12 5 12 5s-7.6 0-8.6.5c-.8.3-1.5 1-1.8 1.8C1.2 8.3 1 9.8 1 12c0 2.2.2 3.7.6 4.7.3.8 1 1.5 1.8 1.8.9.5 8.6.5 8.6.5s7.6 0 8.6-.5c.8-.3 1.5-1 1.8-1.8.4-1 .6-2.5.6-4.7zM10 15.5v-7l6 3.5-6 3.5z"/></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold tracking-[-0.01em] sm:text-2xl">Lists by Category</h3>
                        <ul class="mt-5 space-y-2.5 text-sm text-slate-300 sm:text-[15px]">
                            <li><a href="#" class="transition hover:text-white">Apartments (17)</a></li>
                            <li><a href="#" class="transition hover:text-white">Condos (8)</a></li>
                            <li><a href="#" class="transition hover:text-white">Houses (5)</a></li>
                            <li><a href="#" class="transition hover:text-white">Industrial (1)</a></li>
                            <li><a href="#" class="transition hover:text-white">Land (1)</a></li>
                            <li><a href="#" class="transition hover:text-white">Offices (2)</a></li>
                            <li><a href="#" class="transition hover:text-white">Retail (4)</a></li>
                            <li><a href="#" class="transition hover:text-white">Villas (4)</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold tracking-[-0.01em] sm:text-2xl">Latest Properties</h3>
                        <div class="mt-5 space-y-3.5">
                            <article class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=240&q=80" alt="Luxury House in Greenville" class="h-14 w-16 rounded-md object-cover sm:h-16 sm:w-20">
                                <div>
                                    <p class="text-sm font-medium text-white sm:text-[15px]">Luxury House in Greenville</p>
                                    <p class="text-sm text-slate-300 sm:text-[15px]">$860,000</p>
                                </div>
                            </article>
                            <article class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=240&q=80" alt="Modern Condo for Sale" class="h-14 w-16 rounded-md object-cover sm:h-16 sm:w-20">
                                <div>
                                    <p class="text-sm font-medium text-white sm:text-[15px]">Modern Condo for Sale</p>
                                    <p class="text-sm text-slate-300 sm:text-[15px]">$150,000</p>
                                </div>
                            </article>
                            <article class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=240&q=80" alt="Apartment with Subunits" class="h-14 w-16 rounded-md object-cover sm:h-16 sm:w-20">
                                <div>
                                    <p class="text-sm font-medium text-white sm:text-[15px]">Apartment with Subunits</p>
                                    <p class="text-sm text-slate-300 sm:text-[15px]">$999 / month</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col items-start justify-between gap-3 text-xs text-slate-400 sm:text-sm md:flex-row md:items-center">
                    <p>Copyright WP Estate. All Rights Reserved.</p>
                    <p>Documentation Video Tutorial Client Support</p>
                </div>
            </div>
        </footer>
        
    </body>
</html>
