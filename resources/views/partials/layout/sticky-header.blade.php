<div class="sticky-site-header fixed inset-x-0 top-0 z-[70] border-b border-slate-200/80 bg-white/95 shadow-[0_8px_28px_rgba(15,23,42,0.08)] backdrop-blur" data-sticky-header>
    <div class="section-shell">
        <nav class="relative flex h-14 items-center justify-between gap-4" aria-label="Sticky navigation" data-responsive-nav>
            <a href="{{ route('home') }}" class="text-[1.2rem] font-extrabold tracking-[0.01em] text-[#1f2a44]">RealScape</a>

            <button data-menu-toggle class="inline-flex h-9 w-9 items-center justify-center rounded border border-slate-300 text-slate-700 md:hidden" aria-label="Toggle sticky menu" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div data-menu-panel class="absolute left-0 right-0 top-[calc(100%+0.4rem)] z-40 hidden flex-col gap-1 rounded-xl border border-slate-200 bg-white p-3 text-[14px] font-semibold text-slate-600 shadow-xl md:static md:z-auto md:flex md:flex-row md:items-center md:gap-4 md:overflow-x-auto md:whitespace-nowrap md:border-0 md:bg-transparent md:p-0 md:shadow-none lg:gap-6" data-sticky-menu>
                <a href="{{ route('home') }}" class="rounded px-2 py-1.5 transition hover:bg-slate-100 hover:text-[#1e73be] md:px-0 md:py-0 md:hover:bg-transparent">Home</a>
                <a href="{{ route('about-us') }}" class="rounded px-2 py-1.5 transition hover:bg-slate-100 hover:text-[#1e73be] md:px-0 md:py-0 md:hover:bg-transparent">About Us</a>
                <a href="{{ route('properties') }}" class="rounded px-2 py-1.5 transition hover:bg-slate-100 hover:text-[#1e73be] md:px-0 md:py-0 md:hover:bg-transparent">Properties</a>
                <a href="{{ route('agents') }}" class="rounded px-2 py-1.5 transition hover:bg-slate-100 hover:text-[#1e73be] md:px-0 md:py-0 md:hover:bg-transparent">Agent</a>
                <a href="{{ route('blog') }}" class="rounded px-2 py-1.5 transition hover:bg-slate-100 hover:text-[#1e73be] md:px-0 md:py-0 md:hover:bg-transparent">Blog</a>
                <a href="{{ route('contact-us') }}" class="rounded px-2 py-1.5 transition hover:bg-slate-100 hover:text-[#1e73be] md:px-0 md:py-0 md:hover:bg-transparent">Contact Us</a>
            </div>
        </nav>
    </div>
</div>
