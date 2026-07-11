<header class="relative isolate overflow-visible bg-[#0f355e]">
    @include('partials.layout.topbar')

    <div class="section-shell relative z-20 py-4 sm:py-6">
        @include('partials.layout.navbar')
    </div>
</header>

<section class="relative h-[380px] sm:h-[480px] overflow-hidden bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80');" data-sticky-trigger>
    <div class="absolute inset-0 bg-[#0f2743]/70"></div>
    <div class="section-shell relative z-10 flex h-full flex-col justify-center text-white">
        <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Properties</h1>
        <p class="mt-2 text-sm font-medium text-white/80">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <span class="mx-2">/</span>
            <span>Properties</span>
        </p>
    </div>
</section>
