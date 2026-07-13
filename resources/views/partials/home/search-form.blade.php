@php
    $categories = \App\Models\Category::whereNull('parent_id')->orderBy('name')->get();
    $cities = \App\Models\City::whereIn('id', function($q) {
        $q->select('city_id')->from('properties')->where('status', 'active')->whereNotNull('city_id');
    })->orderBy('name')->get();
    $areas = \App\Models\Property::where('status', 'active')
        ->whereNotNull('locality')
        ->where('locality', '!=', '')
        ->distinct()
        ->orderBy('locality')
        ->pluck('locality');

    $homeLabelClass = 'block text-[11px] font-bold uppercase tracking-[0.17em] text-[#5f7192]';
    $homeFieldClass = 'h-[50px] w-full rounded-xl border border-[#cdd6e2] bg-[#f4f6f9] px-4 text-[16px] font-semibold text-[#42556f] transition focus:border-[#1e73be] focus:bg-white focus:outline-none appearance-none cursor-pointer';
@endphp

<div class="section-shell relative z-20 pb-6 sm:pb-8">
    <div class="mx-auto max-w-6xl">
        <!-- Intent Tabs -->
        <div class="mx-auto inline-flex items-center gap-1.5 rounded-2xl bg-[linear-gradient(90deg,rgba(66,91,117,0.9),rgba(39,64,90,0.85))] p-1.5 shadow-[0_18px_35px_rgba(10,30,51,0.36)]" id="intent-tabs">
            <button type="button" data-type="sale" class="intent-tab is-active rounded-xl px-8 py-2.5 text-[16px] font-extrabold transition-all duration-200 cursor-pointer sm:min-w-[136px] bg-white text-[#1f2a44]">Sale</button>
            <button type="button" data-type="rent" class="intent-tab rounded-xl px-8 py-2.5 text-[16px] font-bold transition-all duration-200 cursor-pointer sm:min-w-[136px] bg-white/10 hover:bg-white/20 text-white">Rent</button>
            <button type="button" data-type="lease" class="intent-tab rounded-xl px-8 py-2.5 text-[16px] font-bold transition-all duration-200 cursor-pointer sm:min-w-[136px] bg-white/10 hover:bg-white/20 text-white">Lease</button>
        </div>

        <!-- Main Search Form Card -->
        <div class="mt-2 rounded-2xl border border-[#c7d2e0] bg-[#eef1f5]/95 p-4 shadow-[0_24px_65px_rgba(8,37,68,0.25)] backdrop-blur-sm sm:p-6">
            <form action="{{ url('/properties') }}" method="GET" id="main-search-form" class="space-y-4">
                <input type="hidden" name="type" id="search-intent-type" value="sale">

                <!-- Row 1: Categories, Cities, Areas, Beds | Baths -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Categories -->
                    <div class="space-y-1.5">
                        <span class="{{ $homeLabelClass }}">Categories</span>
                        <div class="relative">
                            <select name="category" class="{{ $homeFieldClass }}">
                                <option value="">Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Cities -->
                    <div class="space-y-1.5">
                        <span class="{{ $homeLabelClass }}">Cities</span>
                        <div class="relative">
                            <select name="city" class="{{ $homeFieldClass }}">
                                <option value="">Cities</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Areas -->
                    <div class="space-y-1.5">
                        <span class="{{ $homeLabelClass }}">Areas</span>
                        <div class="relative">
                            <select name="area" class="{{ $homeFieldClass }}">
                                <option value="">Areas</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area }}">{{ $area }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Beds | Baths -->
                    <div class="space-y-1.5">
                        <span class="{{ $homeLabelClass }}">Beds | Baths</span>
                        <div class="relative">
                            <select name="beds" class="{{ $homeFieldClass }}">
                                <option value="">Beds | Baths</option>
                                <option value="1">1+ Beds</option>
                                <option value="2">2+ Beds</option>
                                <option value="3">3+ Beds</option>
                                <option value="4">4+ Beds</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Property Status, Stories, Dynamic Price, Search Button -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Property Status -->
                    <div class="space-y-1.5">
                        <span class="{{ $homeLabelClass }}">Property Status</span>
                        <div class="relative">
                            <select name="status" class="{{ $homeFieldClass }}">
                                <option value="">Property Status</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="sold">Sold</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Stories -->
                    <div class="space-y-1.5">
                        <span class="{{ $homeLabelClass }}">Stories</span>
                        <div class="relative">
                            <select name="floors" class="{{ $homeFieldClass }}">
                                <option value="">Stories</option>
                                <option value="1">1 Story</option>
                                <option value="2">2 Stories</option>
                                <option value="3">3 Stories</option>
                                <option value="4">4+ Stories</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Price (Sale / Rental / Invest Price) -->
                    <div class="space-y-1.5">
                        <span class="{{ $homeLabelClass }}" id="price-label">Sale Price</span>
                        <div class="relative">
                            <select name="max_price" id="price-select" class="{{ $homeFieldClass }}">
                                <option value="" id="price-placeholder">Sale Price</option>
                                <option value="100000">Under $100k</option>
                                <option value="250000">Under $250k</option>
                                <option value="500000">Under $500k</option>
                                <option value="750000">Under $750k</option>
                                <option value="1000000">Under $1M</option>
                                <option value="2000000">Under $2M</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="flex items-end">
                        <button type="submit" class="inline-flex h-[50px] w-full items-center justify-center rounded-xl bg-[#1e73be] px-6 text-base font-bold leading-none text-white shadow-[0_12px_24px_rgba(30,115,190,0.34)] transition hover:bg-[#145d9f] cursor-pointer">
                            Search Properties
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.intent-tab');
        const intentInput = document.getElementById('search-intent-type');
        const priceLabel = document.getElementById('price-label');
        const pricePlaceholder = document.getElementById('price-placeholder');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                // Update active classes for clicked tab
                tabs.forEach(t => {
                    t.classList.remove('bg-white', 'text-[#1f2a44]', 'font-extrabold');
                    t.classList.add('bg-white/10', 'hover:bg-white/20', 'text-white', 'font-bold');
                });
                this.classList.add('bg-white', 'text-[#1f2a44]', 'font-extrabold');
                this.classList.remove('bg-white/10', 'hover:bg-white/20', 'text-white', 'font-bold');

                // Update hidden type value
                const type = this.getAttribute('data-type');
                intentInput.value = type;

                // Dynamically update labels
                if (type === 'sale') {
                    priceLabel.textContent = 'Sale Price';
                    pricePlaceholder.textContent = 'Sale Price';
                } else if (type === 'rent') {
                    priceLabel.textContent = 'Rent Price';
                    pricePlaceholder.textContent = 'Rent Price';
                } else {
                    priceLabel.textContent = 'Lease Price';
                    pricePlaceholder.textContent = 'Lease Price';
                }
            });
        });
    });
</script>
