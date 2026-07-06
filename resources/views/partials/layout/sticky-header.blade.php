<div class="sticky-site-header fixed inset-x-0 top-0 z-[70] border-b border-slate-200/80 bg-white/95 shadow-[0_8px_28px_rgba(15,23,42,0.08)] backdrop-blur" data-sticky-header>
    <div class="section-shell">
        <nav class="flex h-14 items-center justify-between gap-4" aria-label="Sticky navigation">
            <a href="{{ route('home') }}" class="text-[1.2rem] font-extrabold tracking-[0.01em] text-[#1f2a44]">RealScape</a>

            <div class="flex items-center gap-4 overflow-x-auto whitespace-nowrap text-[14px] font-semibold text-slate-600 sm:gap-6" data-sticky-menu>
                <a href="{{ route('home') }}" class="transition hover:text-[#1e73be]">Home</a>
                <a href="{{ route('about-us') }}" class="transition hover:text-[#1e73be]">About Us</a>
                <a href="{{ route('properties') }}" class="transition hover:text-[#1e73be]">Properties</a>
                <a href="{{ route('agents') }}" class="transition hover:text-[#1e73be]">Agent</a>
                <a href="{{ route('blog') }}" class="transition hover:text-[#1e73be]">Blog</a>
                <a href="{{ route('contact-us') }}" class="transition hover:text-[#1e73be]">Contact Us</a>
            </div>
        </nav>
    </div>
</div>
