<article class="property-card overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
    <div class="relative">
        <a href="{{ route('property-detail') }}" class="block">
            <img src="{{ $image }}" alt="{{ $title }}" class="h-56 w-full object-cover">
        </a>
        <div class="absolute right-3 top-3 flex items-center gap-1 text-[10px] font-semibold uppercase text-white">
            <span class="rounded bg-blue-600 px-2 py-1">Rentals</span>
            <span class="rounded bg-blue-500 px-2 py-1">{{ $status }}</span>
        </div>
        @if(!empty($featured) && $featured)
            <div class="absolute left-3 top-3 flex items-center gap-1 text-[10px] font-semibold uppercase text-white">
                <span class="rounded bg-emerald-500 px-2 py-1">Featured</span>
            </div>
        @endif
        <p class="absolute bottom-3 left-3 text-xs font-semibold text-white">{{ $location }}</p>
        <p class="absolute bottom-3 right-3 text-xs font-semibold text-white">{{ $tag }}</p>
    </div>
    <div class="p-4">
        <h3 class="property-card-title"><a href="{{ route('property-detail') }}" class="hover:text-[#1e73be]">{{ $title }}</a></h3>
        <p class="property-card-price mt-1">{{ $price }}</p>
        <p class="property-card-copy mt-3">{{ $description }}</p>
        <div class="property-card-meta mt-4 flex items-center gap-4 border-b border-slate-100 pb-3">
            <span>{{ $beds }}</span>
            <span>{{ $baths }}</span>
            <span>{{ $size }}</span>
        </div>
        <div class="mt-3 flex items-center justify-between">
            <p class="property-card-agent">{{ $agent }}</p>
            <div class="flex gap-1 text-slate-400">
                <span class="rounded border border-slate-200 px-2 py-1">+</span>
                <span class="rounded border border-slate-200 px-2 py-1">♡</span>
            </div>
        </div>
    </div>
</article>
