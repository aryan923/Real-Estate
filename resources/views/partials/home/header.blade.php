<header class="relative isolate overflow-visible bg-[#0f355e]">
    <img src="{{ asset('images/hero-home.webp') }}" alt="Hero home" class="absolute inset-0 h-full w-full object-cover" onerror="this.style.display='none'">
    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(12,46,78,0.74)_0%,rgba(11,47,79,0.84)_52%,rgba(9,39,66,0.9)_100%)]"></div>

    @include('partials.layout.topbar')

    <div class="section-shell relative z-20 py-4 sm:py-6">
        @include('partials.layout.navbar')
        @include('partials.home.hero')
    </div>

    @include('partials.home.search-form')
</header>
