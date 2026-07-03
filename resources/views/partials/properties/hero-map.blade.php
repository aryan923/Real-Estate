<header class="relative isolate overflow-visible bg-[#0f355e]">
    @include('partials.layout.topbar')

    <div class="section-shell relative z-20 py-4 sm:py-6">
        @include('partials.layout.navbar')
    </div>
</header>

<section class="relative bg-[#f3f5f7]">
    <div class="relative h-[300px] overflow-hidden border-t border-slate-200 sm:h-[360px]">
        <iframe
            title="Properties map"
            src="https://www.google.com/maps?q=22.5726,88.3639&z=12&output=embed"
            class="h-full w-full border-0"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
        ></iframe>

        <div class="absolute right-4 top-4 z-10 hidden items-center gap-1 rounded-md bg-white/95 p-1 shadow-sm lg:flex">
            <a href="https://google.com/maps?q=22.5726,88.3639" target="_blank" rel="noopener" class="rounded px-2.5 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">View</a>
            <a href="https://google.com/maps?q=22.5726,88.3639" target="_blank" rel="noopener" class="rounded px-2.5 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">My Location</a>
            <a href="https://google.com/maps?q=22.5726,88.3639" target="_blank" rel="noopener" class="rounded px-2.5 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Fullscreen</a>
            <a href="https://google.com/maps?q=22.5726,88.3639" target="_blank" rel="noopener" class="rounded px-2.5 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Prev</a>
            <a href="https://google.com/maps?q=22.5726,88.3639" target="_blank" rel="noopener" class="rounded px-2.5 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Next</a>
        </div>

        <div class="absolute bottom-3 left-1/2 z-10 flex -translate-x-1/2 gap-2 rounded-md bg-white/90 p-1 shadow-sm" data-map-tabs>
            <button type="button" data-map-intent-tab="sales" class="map-intent-tab is-active rounded bg-[#1e73be] px-6 py-2 text-sm font-semibold text-white">Sales</button>
            <button type="button" data-map-intent-tab="rentals" class="map-intent-tab rounded bg-[#d8e8fb] px-6 py-2 text-sm font-semibold text-[#1e73be]">Rentals</button>
            <button type="button" data-map-intent-tab="invest" class="map-intent-tab rounded bg-[#d8e8fb] px-6 py-2 text-sm font-semibold text-[#1e73be]">Invest</button>
        </div>

        <a href="https://google.com/maps?q=22.5726,88.3639" target="_blank" rel="noopener" class="absolute bottom-4 right-4 rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">open map</a>
    </div>

    <div class="relative z-10 mx-auto -mt-4 max-w-[1220px] px-4 pb-7 sm:-mt-6 sm:px-6 lg:px-8" data-map-forms>
        <div data-map-form="sales" class="rounded-2xl border border-slate-200/80 bg-white/95 p-3 shadow-[0_14px_38px_rgba(15,23,42,0.12)] backdrop-blur-sm sm:p-4">
            <form class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Categories</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Cities</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Areas</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Beds | Baths</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Property Status</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Stories</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Sale Price</option></select>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#1e73be] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#145d9f]">Search Properties</button>
            </form>
        </div>

        <div data-map-form="rentals" class="hidden rounded-2xl border border-slate-200/80 bg-white/95 p-3 shadow-[0_14px_38px_rgba(15,23,42,0.12)] backdrop-blur-sm sm:p-4">
            <form class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>States</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Cities</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Areas</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Beds | Baths</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Min size</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Rental Price</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none"><option>Categories</option></select>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#1e73be] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#145d9f]">Search Properties</button>
            </form>
        </div>

        <div data-map-form="invest" class="hidden rounded-2xl border border-slate-200/80 bg-white/95 p-3 shadow-[0_14px_38px_rgba(15,23,42,0.12)] backdrop-blur-sm sm:p-4">
            <form class="grid gap-2 sm:grid-cols-2 lg:grid-cols-12">
                <input type="text" placeholder="Enter an address, state, city, area or zip code" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 placeholder:text-slate-500 focus:border-[#1e73be] focus:outline-none lg:col-span-6">
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none lg:col-span-3"><option>Beds | Baths</option></select>
                <input type="text" placeholder="Keyword" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 placeholder:text-slate-500 focus:border-[#1e73be] focus:outline-none lg:col-span-3">
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none lg:col-span-3"><option>Min Size</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none lg:col-span-3"><option>Categories</option></select>
                <select class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 focus:border-[#1e73be] focus:outline-none lg:col-span-3"><option>Invest Price</option></select>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#1e73be] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#145d9f] lg:col-span-3">Search Properties</button>
            </form>
        </div>
    </div>
</section>
