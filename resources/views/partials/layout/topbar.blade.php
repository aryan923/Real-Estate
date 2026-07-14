@php($solidHeader = $solidHeader ?? false)

<div class="relative z-20 border-b {{ $solidHeader ? 'border-[#b8c7d6] bg-[#7f95ab]' : 'border-white/20 bg-[#0c355b]/35' }}">
    <div class="section-shell hidden h-10 items-center justify-between text-[11px] font-semibold uppercase tracking-[0.05em] text-white/90 lg:flex">
        <div class="flex items-stretch border-l border-white/18">
            @if(!empty($settings['social_facebook']))
                <a href="{{ $settings['social_facebook'] }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition" aria-label="Facebook">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 9H16V6h-2.5C11.6 6 10 7.6 10 9.5V12H8v3h2v6h3v-6h2.3l.7-3H13v-2.2c0-.5.2-.8.5-.8z"/></svg>
                </a>
            @endif
            @if(!empty($settings['social_x']))
                <a href="{{ $settings['social_x'] }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition" aria-label="X">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H22l-6.8 7.8L23 21h-6.2l-4.9-6.4L6.3 21H3.1l7.3-8.3L1 3h6.3l4.4 5.9L18.9 3z"/></svg>
                </a>
            @endif
            @if(!empty($settings['social_linkedin']))
                <a href="{{ $settings['social_linkedin'] }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition" aria-label="LinkedIn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5A1.9 1.9 0 1 0 6.9 4.7a1.9 1.9 0 0 0 0 3.8zM5.2 10h3.4v9.3H5.2V10zm5.4 0H14v1.3h.1c.5-.9 1.6-1.6 3.2-1.6 3.5 0 4.1 2.3 4.1 5.3v4.3H18v-3.8c0-.9 0-2.1-1.3-2.1s-1.5 1-1.5 2v4H12V10z"/></svg>
                </a>
            @endif
            @if(!empty($settings['social_instagram']))
                <a href="{{ $settings['social_instagram'] }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center border-r border-white/18 hover:bg-white/10 transition" aria-label="Instagram">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051C.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
                </a>
            @endif
        </div>
        <div class="flex items-center gap-8 text-[12px] font-semibold normal-case tracking-normal">
            <a href="mailto:{{ $settings['contact_email'] ?? 'contact@mail.com' }}" class="inline-flex items-center gap-2 hover:text-white/80 transition">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $settings['contact_email'] ?? 'contact@mail.com' }}
            </a>
            <a href="tel:{{ $settings['contact_phone'] ?? '+1 408 167 1234' }}" class="inline-flex items-center gap-2 hover:text-white/80 transition">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-2.2 2.2a15.045 15.045 0 01-6.59-6.59l2.2-2.2c.28-.28.36-.67.25-1.02C8.79 6.34 8.59 5.15 8.59 3.92c0-.55-.45-1-1-1H3.99c-.55 0-1 .45-1 1 0 9.39 7.63 17.02 17 17.02.55 0 1-.45 1-1v-3.59c0-.55-.45-1-1-1z"/></svg>
                {{ $settings['contact_phone'] ?? '+1 408 167 1234' }}
            </a>
        </div>
    </div>
</div>
