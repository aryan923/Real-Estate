@php($solidHeader = $solidHeader ?? false)

<nav class="flex items-center justify-between rounded-xl border px-4 py-3.5 text-white sm:px-5 lg:px-7 {{ $solidHeader ? 'border-[#b8c7d6] bg-[#afbdd0]' : 'border-white/20 bg-white/8 backdrop-blur-sm' }}">
    <a href="{{ route('home') }}" class="flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-md border border-white/30 bg-gradient-to-br from-[#2a8def] to-[#0f2f54] text-sm font-extrabold text-white">RS</span>
        <span class="text-[1.45rem] font-extrabold tracking-[0.01em] sm:text-[1.6rem]">RealScape</span>
    </a>

    <button id="menu-toggle" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/30 text-white lg:hidden" aria-label="Toggle menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div id="menu-panel" class="hidden items-center gap-7 lg:flex">
        <a href="{{ route('home') }}" class="text-[15px] font-bold text-white/95 hover:text-white">Home</a>
        <a href="{{ route('about-us') }}" class="text-[15px] font-semibold text-white/95 hover:text-white">About Us</a>
        <a href="{{ route('properties') }}" class="text-[15px] font-semibold text-white/95 hover:text-white">Properties</a>
        <a href="{{ route('agents') }}" class="text-[15px] font-semibold text-white/95 hover:text-white">Agent</a>
        <a href="{{ route('blog') }}" class="text-[15px] font-semibold text-white/95 hover:text-white">Blog</a>
        <a href="{{ route('contact-us') }}" class="text-[15px] font-semibold text-white/95 hover:text-white">Contact Us</a>
    </div>
</nav>
