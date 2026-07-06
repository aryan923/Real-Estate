@php($solidHeader = $solidHeader ?? false)

<div class="relative z-20 border-b {{ $solidHeader ? 'border-[#b8c7d6] bg-[#7f95ab]' : 'border-white/20 bg-[#0c355b]/35' }}">
    <div class="section-shell hidden h-10 items-center justify-between text-[11px] font-semibold uppercase tracking-[0.05em] text-white/90 lg:flex">
        <div class="flex items-stretch border-l border-r border-white/18">
            <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">f</span>
            <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">x</span>
            <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">in</span>
            <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">p</span>
            <span class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18">o</span>
            <span class="inline-flex h-10 w-10 items-center justify-center">v</span>
        </div>
        <div class="flex items-center gap-8 text-[12px] font-semibold normal-case tracking-normal">
            <span class="inline-flex items-center gap-1.5">❤ Favorites</span>
            <span class="inline-flex items-center gap-1.5">✉ contact@mail.com</span>
            <span class="inline-flex items-center gap-1.5">☎ +1 408 167 1234</span>
        </div>
    </div>
</div>
