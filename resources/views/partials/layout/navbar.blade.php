@php($solidHeader = $solidHeader ?? false)

<nav class="relative flex items-center justify-between rounded-xl border px-4 py-3.5 text-white sm:px-5 lg:px-7 {{ $solidHeader ? 'border-[#b8c7d6] bg-[#afbdd0]' : 'border-white/20 bg-white/8 backdrop-blur-sm' }}" data-responsive-nav>
    <a href="{{ route('home') }}" class="flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-md border border-white/30 bg-gradient-to-br from-[#2a8def] to-[#0f2f54] text-sm font-extrabold text-white">RS</span>
        <span class="text-[1.45rem] font-extrabold tracking-[0.01em] sm:text-[1.6rem]">RealScape</span>
    </a>

    <button data-menu-toggle class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/30 text-white lg:hidden" aria-label="Toggle menu" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div data-menu-panel class="absolute left-0 right-0 top-[calc(100%+0.55rem)] z-40 hidden flex-col gap-1 rounded-xl border border-white/25 bg-[#0f2f54]/95 p-4 shadow-xl backdrop-blur lg:static lg:z-auto lg:flex lg:flex-row lg:items-center lg:gap-7 lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none lg:backdrop-blur-0">
        <a href="{{ route('home') }}" class="rounded px-2 py-1.5 text-[15px] font-bold text-white/95 hover:bg-white/10 hover:text-white lg:px-0 lg:py-0 lg:hover:bg-transparent">Home</a>
        <a href="{{ route('about-us') }}" class="rounded px-2 py-1.5 text-[15px] font-semibold text-white/95 hover:bg-white/10 hover:text-white lg:px-0 lg:py-0 lg:hover:bg-transparent">About Us</a>
        <a href="{{ route('properties') }}" class="rounded px-2 py-1.5 text-[15px] font-semibold text-white/95 hover:bg-white/10 hover:text-white lg:px-0 lg:py-0 lg:hover:bg-transparent">Properties</a>
        <a href="{{ route('agents') }}" class="rounded px-2 py-1.5 text-[15px] font-semibold text-white/95 hover:bg-white/10 hover:text-white lg:px-0 lg:py-0 lg:hover:bg-transparent">Agent</a>
        <a href="{{ route('blog') }}" class="rounded px-2 py-1.5 text-[15px] font-semibold text-white/95 hover:bg-white/10 hover:text-white lg:px-0 lg:py-0 lg:hover:bg-transparent">Blog</a>
        <a href="{{ route('contact-us') }}" class="rounded px-2 py-1.5 text-[15px] font-semibold text-white/95 hover:bg-white/10 hover:text-white lg:px-0 lg:py-0 lg:hover:bg-transparent">Contact Us</a>
    </div>
</nav>
