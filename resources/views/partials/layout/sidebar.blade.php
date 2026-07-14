@php
    $footerCategories = \App\Models\Category::withCount(['properties' => function ($q) {
        $q->where('status', 'active');
    }])
    ->orderByDesc('properties_count')
    ->take(8)
    ->get();
@endphp
<aside class="rounded-lg bg-[#0a2f52] p-6 text-white shadow-sm ring-1 ring-white/10">
    <h3 class="text-xl font-semibold tracking-[-0.01em]">Lists by Category</h3>
    <ul class="mt-5 space-y-2.5 text-sm text-slate-300 sm:text-[15px]">
        @foreach($footerCategories as $fcat)
            <li><a href="{{ route('properties', ['category' => $fcat->id]) }}" class="transition hover:text-white">{{ $fcat->name }} ({{ $fcat->properties_count }})</a></li>
        @endforeach
    </ul>
</aside>
