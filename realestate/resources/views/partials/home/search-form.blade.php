<div class="section-shell relative z-20 pb-6 sm:pb-8">
    <div class="mx-auto max-w-6xl">
        <div class="mx-auto inline-flex items-center gap-1 rounded-2xl bg-white/15 p-1.5 shadow-xl shadow-[#082544]/35 backdrop-blur-md" id="intent-tabs">
            <button type="button" data-intent="buy" class="intent-tab rounded-xl bg-white px-6 py-2.5 text-[0.82rem] font-bold text-slate-900 shadow-sm sm:px-10 sm:text-[1.15rem]">Sales</button>
            <button type="button" data-intent="rent" class="intent-tab rounded-xl px-6 py-2.5 text-[0.82rem] font-bold text-white/90 sm:px-10 sm:text-[1.15rem]">Rentals</button>
            <button type="button" data-intent="sell" class="intent-tab rounded-xl px-6 py-2.5 text-[0.82rem] font-bold text-white/90 sm:px-10 sm:text-[1.15rem]">Invest</button>
        </div>

        <div class="mt-2 rounded-2xl bg-white p-4 shadow-[0_24px_65px_rgba(8,37,68,0.28)] ring-1 ring-slate-100 sm:p-6">
            <form id="property-search" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-12 xl:gap-4">
                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-3">
                    <span class="block text-xs font-bold uppercase tracking-[0.15em] text-slate-500">Category</span>
                    <select id="search-type" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 transition focus:border-[#2f92f2] focus:bg-white focus:outline-none sm:text-base">
                        <option value="all">Property Category</option>
                        <option value="villa">Villa</option>
                        <option value="apartment">Apartment</option>
                        <option value="townhouse">Townhouse</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-3">
                    <span class="block text-xs font-bold uppercase tracking-[0.15em] text-slate-500">City</span>
                    <select id="search-city" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 transition focus:border-[#2f92f2] focus:bg-white focus:outline-none sm:text-base">
                        <option value="all">Property City</option>
                        <option value="miami">Miami</option>
                        <option value="dallas">Dallas</option>
                        <option value="new-york">New York</option>
                        <option value="seattle">Seattle</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="block text-xs font-bold uppercase tracking-[0.15em] text-slate-500">Beds and Baths</span>
                    <select id="search-bed" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 transition focus:border-[#2f92f2] focus:bg-white focus:outline-none sm:text-base">
                        <option value="all">Beds | Baths</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                    </select>
                </label>

                <label class="space-y-1.5 text-sm font-semibold text-slate-700 sm:text-base xl:col-span-2">
                    <span class="block text-xs font-bold uppercase tracking-[0.15em] text-slate-500">Budget</span>
                    <select id="search-price" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 transition focus:border-[#2f92f2] focus:bg-white focus:outline-none sm:text-base">
                        <option value="all">Sale Price</option>
                        <option value="500000">$500k</option>
                        <option value="850000">$850k</option>
                        <option value="1200000">$1.2M</option>
                    </select>
                </label>

                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#0f6fcb] px-6 py-3 text-base font-semibold text-white transition hover:bg-[#0c5eac] xl:col-span-2 xl:self-end">Search Properties</button>
            </form>
        </div>
    </div>
</div>
