<article class="property-card overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
    data-intent="{{ $intent }}" data-city="{{ $cityKey }}" data-type="{{ $typeKey }}" data-bed="{{ $beds }}" data-price="{{ $priceValue }}">
    <a href="{{ route('property-detail') }}" class="block">
        <img src="{{ $image }}" alt="{{ $title }}" class="h-52 w-full object-cover">
    </a>
    <div class="p-4">
        <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide text-white">
            <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
            <div class="flex gap-1">
                <span class="rounded bg-blue-600 px-2 py-1">{{ $badgeOne }}</span>
                <span class="rounded bg-blue-500 px-2 py-1">{{ $badgeTwo }}</span>
            </div>
        </div>
        <h3 class="property-card-title mt-4"><a href="{{ route('property-detail') }}" class="hover:text-[#1e73be]">{{ $title }}</a></h3>
        <p class="property-card-price mt-1">{{ $priceText }}</p>
        <p class="property-card-copy mt-3">{{ $description }}</p>
        <p class="property-card-meta mt-3">{{ $location }}</p>
    </div>
</article>
