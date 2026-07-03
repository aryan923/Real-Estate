<article class="property-card overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
    data-intent="{{ $intent }}" data-city="{{ $cityKey }}" data-type="{{ $typeKey }}" data-bed="{{ $beds }}" data-price="{{ $priceValue }}">
    <img src="{{ $image }}" alt="{{ $title }}" class="h-52 w-full object-cover">
    <div class="p-4">
        <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
            <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
            <div class="flex gap-1">
                <span class="rounded bg-blue-600 px-2 py-1">{{ $badgeOne }}</span>
                <span class="rounded bg-blue-500 px-2 py-1">{{ $badgeTwo }}</span>
            </div>
        </div>
        <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $title }}</h3>
        <p class="mt-1 text-sm font-semibold text-blue-600">{{ $priceText }}</p>
        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
        <p class="mt-3 text-sm text-slate-400">{{ $location }}</p>
    </div>
</article>
