<footer class="w-full bg-[#041a30] py-12 text-white sm:py-14">
    <div class="section-shell">
        <div class="grid gap-8 border-b border-white/12 pb-8 lg:grid-cols-[1.2fr_0.9fr_1fr] lg:gap-12">
            <div>
                <h3 class="text-xl font-semibold tracking-[-0.01em] sm:text-2xl">Contact us</h3>
                <ul class="mt-5 space-y-3 text-sm leading-6 text-slate-300 sm:text-[15px]">
                    @if(!empty($settings['footer_address']))
                        <li>{{ $settings['footer_address'] }}</li>
                    @else
                        <li>3755 Commercial St SE Salem, Corner with Sunny Boulevard, 3755 Commercial OR 97302</li>
                    @endif

                    @if(!empty($settings['footer_phone_1']))
                        <li><a href="tel:{{ $settings['footer_phone_1'] }}" class="hover:underline hover:text-white transition">{{ $settings['footer_phone_1'] }}</a></li>
                    @endif

                    @if(!empty($settings['footer_phone_2']))
                        <li><a href="tel:{{ $settings['footer_phone_2'] }}" class="hover:underline hover:text-white transition">{{ $settings['footer_phone_2'] }}</a></li>
                    @endif

                    @if(empty($settings['footer_phone_1']) && empty($settings['footer_phone_2']))
                        <li><a href="tel:3055554446" class="hover:underline hover:text-white transition">(305) 555-4446</a></li>
                        <li><a href="tel:3055554555" class="hover:underline hover:text-white transition">(305) 555-4555</a></li>
                    @endif

                    @if(!empty($settings['footer_email']))
                        <li><a href="mailto:{{ $settings['footer_email'] }}" class="hover:underline hover:text-white transition">{{ $settings['footer_email'] }}</a></li>
                    @else
                        <li><a href="mailto:youremail@gmail.com" class="hover:underline hover:text-white transition">youremail@gmail.com</a></li>
                    @endif

                    @if(!empty($settings['footer_about']))
                        <li>{{ $settings['footer_about'] }}</li>
                    @elseif(!empty($settings['site_name']))
                        <li>{{ $settings['site_name'] }}</li>
                    @endif
                </ul>

                <!-- Dynamic Social Icons -->
                <div class="mt-7 flex flex-wrap gap-2.5">
                    @if(!empty($settings['social_facebook']))
                        <a href="{{ $settings['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="Facebook">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 9H16V6h-2.5C11.6 6 10 7.6 10 9.5V12H8v3h2v6h3v-6h2.3l.7-3H13v-2.2c0-.5.2-.8.5-.8z"/></svg>
                        </a>
                    @endif
                    @if(!empty($settings['social_x']))
                        <a href="{{ $settings['social_x'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="X">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H22l-6.8 7.8L23 21h-6.2l-4.9-6.4L6.3 21H3.1l7.3-8.3L1 3h6.3l4.4 5.9L18.9 3z"/></svg>
                        </a>
                    @endif
                    @if(!empty($settings['social_linkedin']))
                        <a href="{{ $settings['social_linkedin'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="LinkedIn">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.9 8.5A1.9 1.9 0 1 0 6.9 4.7a1.9 1.9 0 0 0 0 3.8zM5.2 10h3.4v9.3H5.2V10zm5.4 0H14v1.3h.1c.5-.9 1.6-1.6 3.2-1.6 3.5 0 4.1 2.3 4.1 5.3v4.3H18v-3.8c0-.9 0-2.1-1.3-2.1s-1.5 1-1.5 2v4H12V10z"/></svg>
                        </a>
                    @endif
                    @if(!empty($settings['social_instagram']))
                        <a href="{{ $settings['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-slate-100 transition hover:bg-white hover:text-[#041a30]" aria-label="Instagram">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051C.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            @include('partials.layout.sidebar')

            <div>
                <h3 class="text-xl font-semibold tracking-[-0.01em] sm:text-2xl">Latest Properties</h3>
                <div class="mt-5 space-y-3.5">
                    @php($latestProperties = \App\Models\Property::where('status', 'active')->latest()->take(3)->get())
                    @forelse($latestProperties as $lprop)
                        @php($primary = $lprop->images?->firstWhere('is_primary', true) ?? $lprop->images?->first())
                        <article class="flex items-center gap-3">
                            <a href="{{ route('property-detail', $lprop->slug) }}" class="flex items-center gap-3 group w-full">
                                @if($primary)
                                    <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($primary->path) }}" alt="{{ $lprop->title }}" class="h-14 w-16 rounded-md object-cover sm:h-16 sm:w-20 group-hover:opacity-80 transition flex-shrink-0">
                                @else
                                    <img src="https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=240&q=80" alt="{{ $lprop->title }}" class="h-14 w-16 rounded-md object-cover sm:h-16 sm:w-20 group-hover:opacity-80 transition flex-shrink-0">
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-white sm:text-[15px] group-hover:text-blue-400 transition">{{ $lprop->title }}</p>
                                    <p class="text-sm text-slate-300 sm:text-[15px]">{{ $lprop->price ? '$' . number_format($lprop->price) : 'Contact for Price' }}</p>
                                </div>
                            </a>
                        </article>
                    @empty
                        <p class="text-sm text-slate-400">No properties available.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col items-start justify-between gap-3 text-xs text-slate-400 sm:text-sm md:flex-row md:items-center">
            @if(!empty($settings['footer_copyright']))
                <p>{{ $settings['footer_copyright'] }}</p>
            @else
                <p>Copyright {{ $settings['site_name'] ?? '' }}. All Rights Reserved.</p>
            @endif

            <div class="flex gap-4">
                <a href="{{ route('terms') }}" class="hover:text-white transition">Terms & Conditions</a>
                <a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
