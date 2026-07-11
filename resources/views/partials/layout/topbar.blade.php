@php($solidHeader = $solidHeader ?? false)

<div class="relative z-20 border-b {{ $solidHeader ? 'border-[#b8c7d6] bg-[#7f95ab]' : 'border-white/20 bg-[#0c355b]/35' }}">
    <div class="section-shell hidden h-10 items-center justify-between text-[11px] font-semibold uppercase tracking-[0.05em] text-white/90 lg:flex">
        <div class="flex items-stretch border-l border-r border-white/18">
            <a href="{{ $settings['social_facebook'] ?? '#' }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition">f</a>
            <a href="{{ $settings['social_x'] ?? '#' }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition">x</a>
            <a href="{{ $settings['social_linkedin'] ?? '#' }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition">in</a>
            <a href="#" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition">p</a>
            <a href="{{ $settings['social_instagram'] ?? '#' }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition">o</a>
            <a href="#" class="inline-flex h-10 w-10 items-center justify-center hover:bg-white/10 transition">v</a>
        </div>
        <div class="flex items-center gap-8 text-[12px] font-semibold normal-case tracking-normal">
            <span class="inline-flex items-center gap-1.5">❤ Favorites</span>
            <a href="mailto:{{ $settings['contact_email'] ?? 'contact@mail.com' }}" class="inline-flex items-center gap-1.5 hover:text-white/80 transition">✉ {{ $settings['contact_email'] ?? 'contact@mail.com' }}</a>
            <a href="tel:{{ $settings['contact_phone'] ?? '+1 408 167 1234' }}" class="inline-flex items-center gap-1.5 hover:text-white/80 transition">☎ {{ $settings['contact_phone'] ?? '+1 408 167 1234' }}</a>
        </div>
    </div>
</div>
