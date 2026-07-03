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
        <h3 class="mt-4 text-[24px] font-semibold leading-tight tracking-[-0.02em] text-[#1f2a44]"><a href="{{ route('property-detail') }}" class="hover:text-[#1e73be]">{{ $title }}</a></h3>
        <p class="mt-1 text-[24px] font-bold leading-none text-[#1e73be]">{{ $priceText }}</p>
        <p class="mt-3 text-[15px] leading-7 text-slate-600">{{ $description }}</p>
        <p class="mt-3 text-sm text-slate-400">{{ $location }}</p>
    </div>
</article>
