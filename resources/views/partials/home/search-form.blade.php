@php
    $homeLabelClass = 'block text-[11px] font-bold uppercase tracking-[0.17em] text-[#5f7192]';
    $homeFieldClass = 'h-[50px] w-full rounded-xl border border-[#cdd6e2] bg-[#f4f6f9] px-4 text-[16px] font-semibold text-[#42556f] transition focus:border-[#1e73be] focus:bg-white focus:outline-none';
    $homeSubmitClass = 'inline-flex h-[50px] w-full items-center justify-center rounded-xl bg-[#1e73be] px-6 text-base font-bold leading-none text-white shadow-[0_12px_24px_rgba(30,115,190,0.34)] transition hover:bg-[#145d9f] xl:w-auto';
@endphp

<div class="section-shell relative z-20 pb-6 sm:pb-8">
    <div class="mx-auto max-w-6xl">
        <div class="mx-auto inline-flex items-center gap-1.5 rounded-2xl bg-[linear-gradient(90deg,rgba(66,91,117,0.9),rgba(39,64,90,0.85))] p-1.5 shadow-[0_18px_35px_rgba(10,30,51,0.36)]" id="intent-tabs">
            <button type="button" data-intent="buy" class="intent-tab is-active rounded-xl bg-white px-8 py-2.5 text-[16px] font-extrabold text-[#1f2a44] ring-2 ring-[#1e73be] ring-offset-1 ring-offset-transparent sm:min-w-[136px]">Sales</button>
            <button type="button" data-intent="rent" class="intent-tab rounded-xl bg-[#d8e8fb] px-8 py-2.5 text-[16px] font-bold text-[#0f5faf] sm:min-w-[136px]">Rentals</button>
            <button type="button" data-intent="sell" class="intent-tab rounded-xl bg-[#d8e8fb] px-8 py-2.5 text-[16px] font-bold text-[#0f5faf] sm:min-w-[136px]">Invest</button>
        </div>

        <div class="mt-2 rounded-2xl border border-[#c7d2e0] bg-[#eef1f5]/95 p-4 shadow-[0_24px_65px_rgba(8,37,68,0.25)] backdrop-blur-sm sm:p-6" data-home-intent-forms>
            <form id="property-search" data-home-intent-form="buy" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-12 xl:gap-4">
                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-3">
                    <span class="{{ $homeLabelClass }}">Category</span>
                    <select id="search-type" class="{{ $homeFieldClass }}">
                        <option value="all">Property Category</option>
                        <option value="villa">Villa</option>
                        <option value="apartment">Apartment</option>
                        <option value="townhouse">Townhouse</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-3">
                    <span class="{{ $homeLabelClass }}">City</span>
                    <select id="search-city" class="{{ $homeFieldClass }}">
                        <option value="all">Property City</option>
                        <option value="miami">Miami</option>
                        <option value="dallas">Dallas</option>
                        <option value="new-york">New York</option>
                        <option value="seattle">Seattle</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Beds and Baths</span>
                    <select id="search-bed" class="{{ $homeFieldClass }}">
                        <option value="all">Beds | Baths</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Budget</span>
                    <select id="search-price" class="{{ $homeFieldClass }}">
                        <option value="all">Sale Price</option>
                        <option value="500000">$500k</option>
                        <option value="850000">$850k</option>
                        <option value="1200000">$1.2M</option>
                    </select>
                </label>

                <button type="submit" class="{{ $homeSubmitClass }} xl:col-span-2 xl:self-end">Search Properties</button>
            </form>

            <form data-home-intent-form="rent" class="hidden grid gap-3 sm:grid-cols-2 xl:grid-cols-12 xl:gap-4">
                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-3">
                    <span class="{{ $homeLabelClass }}">States</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>States</option>
                        <option>New York</option>
                        <option>New Jersey</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-3">
                    <span class="{{ $homeLabelClass }}">City</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Cities</option>
                        <option>Jersey City</option>
                        <option>Manhattan</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Area</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Areas</option>
                        <option>West Side</option>
                        <option>Upper East Side</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Beds and Baths</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Beds | Baths</option>
                        <option>2+ Beds</option>
                        <option>3+ Beds</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Min Size</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Min Size</option>
                        <option>500 ft²</option>
                        <option>1000 ft²</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Rental Price</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Rental Price</option>
                        <option>$900 - $1500</option>
                        <option>$1500 - $3000</option>
                    </select>
                </label>

                <button type="submit" class="{{ $homeSubmitClass }} xl:col-span-2 xl:self-end">Search Properties</button>
            </form>

            <form data-home-intent-form="sell" class="hidden grid gap-3 sm:grid-cols-2 xl:grid-cols-12 xl:gap-4">
                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-5">
                    <span class="{{ $homeLabelClass }}">Address</span>
                    <input type="text" placeholder="Enter an address, state, city, area or zip code" class="{{ $homeFieldClass }} placeholder:text-[#5f7192]">
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Beds and Baths</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Beds | Baths</option>
                        <option>2+ Beds</option>
                        <option>3+ Beds</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Keyword</span>
                    <input type="text" placeholder="Keyword" class="{{ $homeFieldClass }} placeholder:text-[#5f7192]">
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Min Size</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Min Size</option>
                        <option>800 ft²</option>
                        <option>1500 ft²</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Categories</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Categories</option>
                        <option>Land</option>
                        <option>Offices</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="{{ $homeLabelClass }}">Invest Price</span>
                    <select class="{{ $homeFieldClass }}">
                        <option>Invest Price</option>
                        <option>$100k - $300k</option>
                        <option>$300k - $700k</option>
                    </select>
                </label>

                <button type="submit" class="{{ $homeSubmitClass }} xl:col-span-2 xl:self-end">Search Properties</button>
            </form>
        </div>
    </div>
</div>
