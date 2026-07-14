@php($solidHeader = $solidHeader ?? false)

<nav class="relative flex items-center justify-between rounded-xl border px-4 py-3.5 text-white sm:px-5 lg:px-7 {{ $solidHeader ? 'border-[#b8c7d6] bg-[#afbdd0]' : 'border-white/20 bg-white/8 backdrop-blur-sm' }}" data-responsive-nav>
    <a href="{{ route('home') }}" class="flex items-center gap-3">
        @if(!empty($settings['site_logo']))
            <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'RealScape' }}" class="h-10 object-contain">
        @else
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-md border border-white/30 bg-gradient-to-br from-[#2a8def] to-[#0f2f54] text-sm font-extrabold text-white">RS</span>
        @endif
        <span class="text-[1.45rem] font-extrabold tracking-[0.01em] sm:text-[1.6rem]">{{ $settings['site_name'] ?? 'RealScape' }}</span>
    </a>

    <button data-menu-toggle class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/30 text-white lg:hidden" aria-label="Toggle menu" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div data-menu-panel class="absolute left-0 right-0 top-[calc(100%+0.55rem)] z-40 hidden flex-col gap-1 rounded-xl border border-white/25 bg-[#0f2f54] p-4 shadow-xl lg:static lg:z-auto lg:flex lg:flex-row lg:items-center lg:gap-7 lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none lg:backdrop-blur-0">
        @php($routes = [
            'home' => 'Home',
            'about-us' => 'About Us',
            'properties' => 'Properties',
            'agents' => 'Agent',
            'blog' => 'Blog',
            'contact-us' => 'Contact Us'
        ])
        @foreach($routes as $route => $label)
            @php($isActive = request()->routeIs($route))
            <a href="{{ route($route) }}" class="relative rounded px-2 py-1.5 text-[15px] font-semibold transition-all lg:rounded-none lg:px-0 lg:py-1 {{ $isActive ? 'text-white border-b-2 border-white font-bold' : 'text-white/80 hover:text-white lg:hover:border-b-2 lg:hover:border-white/50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</nav>
