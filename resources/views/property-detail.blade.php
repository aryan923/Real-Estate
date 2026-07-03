@extends('layouts.master')

@section('title', 'Property Detail')

@section('content')
    <header class="relative isolate overflow-visible bg-[#0f355e]">
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
                <a href="#" class="hover:text-[#1e73be]">Industrial</a>
                <span>›</span>
                <span class="text-[#1e73be]">Suburb Home for Sale</span>
            </div>

            <div class="mt-4 flex flex-wrap items-start justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="rounded bg-[#2f7ecc] px-2.5 py-1 text-xs font-semibold text-white">Invest</span>
                        <span class="rounded bg-[#2f7ecc] px-2.5 py-1 text-xs font-semibold text-white">Industrial</span>
                    </div>
                    <h1 class="mt-2 text-[44px] font-extrabold tracking-[-0.02em] text-[#1f2a44]">Suburb Home for Sale</h1>
                    <p class="mt-2 inline-flex items-center gap-1.5 text-[17px] text-slate-600">
                        <span>📍</span>
                        Lincoln Street, Jersey City, The Heights
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-[38px] font-semibold leading-none text-[#1e73be]">$1,200 <span class="text-[22px]">/ sq ft</span></p>
                    <p class="mt-1 text-[56px] font-extrabold leading-none text-[#0f6fcb]">$1,000,000</p>
                    <div class="mt-4 flex flex-wrap justify-end gap-2">
                        <button type="button" class="inline-flex items-center gap-1.5 rounded bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">↗ Share</button>
                        <button type="button" class="inline-flex items-center gap-1.5 rounded bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">♡ Favorite</button>
                        <button type="button" class="inline-flex items-center gap-1.5 rounded bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">🖨 Print</button>
                    </div>
                </div>
            </div>

            <div class="mt-5 grid gap-3 lg:grid-cols-[minmax(0,1fr)_118px]">
                <div class="relative overflow-hidden rounded-lg border border-slate-300 bg-white">
                    <img id="property-main-image" src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80" alt="Property main view" class="h-[460px] w-full object-cover">

                    <button type="button" id="property-prev" class="absolute left-2 top-1/2 inline-flex h-12 w-8 -translate-y-1/2 items-center justify-center rounded bg-black/45 text-white hover:bg-black/60">‹</button>
                    <button type="button" id="property-next" class="absolute right-2 top-1/2 inline-flex h-12 w-8 -translate-y-1/2 items-center justify-center rounded bg-black/45 text-white hover:bg-black/60">›</button>
                    <span class="absolute right-3 top-3 rounded bg-[#1e73be] px-4 py-1.5 text-sm font-semibold text-white">Active</span>

                    <div class="absolute left-3 top-3 flex gap-2">
                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded bg-[#1e73be] text-white">🖼</button>
                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded bg-white text-slate-700">📍</button>
                    </div>
                </div>

                <div id="property-thumbs" class="grid max-h-[460px] gap-1 overflow-y-auto rounded-lg border border-slate-300 bg-white p-1">
                    <button type="button" class="property-thumb is-active overflow-hidden rounded border-2 border-[#1e73be]" data-image="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80"><img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=300&q=80" alt="thumb 1" class="h-16 w-full object-cover"></button>
                    <button type="button" class="property-thumb overflow-hidden rounded border-2 border-transparent" data-image="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80"><img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=300&q=80" alt="thumb 2" class="h-16 w-full object-cover"></button>
                    <button type="button" class="property-thumb overflow-hidden rounded border-2 border-transparent" data-image="https://images.unsplash.com/photo-1600210492486-724fe5c67fb3?auto=format&fit=crop&w=1600&q=80"><img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb3?auto=format&fit=crop&w=300&q=80" alt="thumb 3" class="h-16 w-full object-cover"></button>
                    <button type="button" class="property-thumb overflow-hidden rounded border-2 border-transparent" data-image="https://images.unsplash.com/photo-1600607687644-c7171b42498f?auto=format&fit=crop&w=1600&q=80"><img src="https://images.unsplash.com/photo-1600607687644-c7171b42498f?auto=format&fit=crop&w=300&q=80" alt="thumb 4" class="h-16 w-full object-cover"></button>
                    <button type="button" class="property-thumb overflow-hidden rounded border-2 border-transparent" data-image="https://images.unsplash.com/photo-1600121848594-d8644e57abab?auto=format&fit=crop&w=1600&q=80"><img src="https://images.unsplash.com/photo-1600121848594-d8644e57abab?auto=format&fit=crop&w=300&q=80" alt="thumb 5" class="h-16 w-full object-cover"></button>
                    <button type="button" class="property-thumb overflow-hidden rounded border-2 border-transparent" data-image="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1600&q=80"><img src="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=300&q=80" alt="thumb 6" class="h-16 w-full object-cover"></button>
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px] lg:items-start">
                <div class="space-y-6">
                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <h2 class="text-2xl font-bold tracking-[-0.02em] text-[#1f2a44]">Description</h2>
                        <div class="mt-4 space-y-4 text-[15px] leading-7 text-slate-600">
                            <p>
                                Mineral Reserves: 2008 core drilling and testing (on just 60 of the 1,100 acres) proved 30-60 million tons of high-grade calcium carbonate. Other reports show high-grade calcium carbonate throughout remainder of the property. Geological reports available.
                            </p>
                            <p>
                                Transportation: Railroad access within 16-18 miles. Rail company may extend existing line to site. Roads: Paved, county-maintained two lane road runs from Blythe to the entrance of the property. Paved roads up to quarry then good gravel roads throughout site. Water: By well (four miles from Colorado River)
                            </p>
                        </div>
                    </article>

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <div class="flex items-center justify-between gap-4">
                            <h2 class="text-2xl font-bold tracking-[-0.02em] text-[#1f2a44]">Address</h2>
                            <span class="text-2xl text-[#1e73be]">⌃</span>
                        </div>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 text-[15px]">
                            <p><span class="font-semibold text-slate-900">Address:</span> <span class="text-slate-600">Lincoln Street</span></p>
                            <p><span class="font-semibold text-slate-900">City:</span> <span class="text-slate-600">Jersey City</span></p>
                            <p><span class="font-semibold text-slate-900">Area:</span> <span class="text-slate-600">The Heights</span></p>
                            <p><span class="font-semibold text-slate-900">State/County:</span> <span class="text-slate-600">New Jersey State</span></p>
                            <p><span class="font-semibold text-slate-900">Zip:</span> <span class="text-slate-600">5874145</span></p>
                            <p><span class="font-semibold text-slate-900">Country:</span> <span class="text-slate-600">United States</span></p>
                        </div>

                        <div class="mt-5">
                            <a href="https://google.com/maps?q=Lincoln+Street+Jersey+City" target="_blank" rel="noopener" class="inline-flex items-center rounded bg-[#67c47c] px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-[#56b36b]">
                                Open In Google Maps
                            </a>
                        </div>
                    </article>

                    <article class="rounded-xl bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                        <div class="flex items-center justify-between gap-4">
                            <h2 class="text-2xl font-bold tracking-[-0.02em] text-[#1f2a44]">Details</h2>
                            <span class="text-2xl text-[#1e73be]">⌃</span>
                        </div>

                        <div class="mt-6 grid gap-x-8 gap-y-3 md:grid-cols-2 lg:grid-cols-3 text-[15px]">
                            <p><span class="font-semibold text-slate-900">Property Id:</span> <span class="text-slate-600">111</span></p>
                            <p><span class="font-semibold text-slate-900">Price:</span> <span class="text-slate-600">$1,000,000</span></p>
                            <p><span class="font-semibold text-slate-900">Price Info:</span> <span class="text-slate-600">$1,200 / sq ft</span></p>
                            <p><span class="font-semibold text-slate-900">Property Size:</span> <span class="text-slate-600">2,900 ft²</span></p>
                            <p><span class="font-semibold text-slate-900">Property Lot Size:</span> <span class="text-slate-600">3,500 ac</span></p>
                            <p><span class="font-semibold text-slate-900">Rooms:</span> <span class="text-slate-600">3</span></p>
                            <p><span class="font-semibold text-slate-900">Bedrooms:</span> <span class="text-slate-600">5</span></p>
                            <p><span class="font-semibold text-slate-900">Bathrooms:</span> <span class="text-slate-600">5</span></p>
                            <p><span class="font-semibold text-slate-900">Year Built:</span> <span class="text-slate-600">1920</span></p>
                            <p><span class="font-semibold text-slate-900">Garage Size:</span> <span class="text-slate-600">200 cars</span></p>
                            <p><span class="font-semibold text-slate-900">Available from:</span> <span class="text-slate-600">2016-05-27</span></p>
                            <p><span class="font-semibold text-slate-900">Exterior Material:</span> <span class="text-slate-600">cement</span></p>
                            <p><span class="font-semibold text-slate-900">Structure Type:</span> <span class="text-slate-600">Brick</span></p>
                            <p><span class="font-semibold text-slate-900">Floors No:</span> <span class="text-slate-600">4</span></p>
                        </div>
                    </article>
                </div>

                <aside class="lg:sticky lg:top-6">
                    <div class="rounded-xl bg-white shadow-[0_10px_30px_rgba(15,23,42,0.08)]">
                        <div class="grid grid-cols-2 border-b border-slate-200 text-center text-[15px] font-semibold">
                            <button type="button" class="border-b-2 border-[#1e73be] px-4 py-3 text-[#1f2a44]">Request Info</button>
                            <button type="button" class="px-4 py-3 text-slate-500">Schedule a tour</button>
                        </div>

                        <div class="p-4 sm:p-5">
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
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
