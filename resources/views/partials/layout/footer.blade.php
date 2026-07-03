<footer class="w-full bg-[#041a30] py-12 text-white sm:py-14">
    <div class="section-shell">
        <div class="grid gap-8 border-b border-white/12 pb-8 lg:grid-cols-[1.2fr_0.9fr_1fr] lg:gap-12">
            <div>
                <h3 class="text-xl font-semibold tracking-[-0.01em] sm:text-2xl">Contact us</h3>
                <ul class="mt-5 space-y-3 text-sm leading-6 text-slate-300 sm:text-[15px]">
                    <li>3755 Commercial St SE Salem, Corner with Sunny Boulevard, 3755 Commercial OR 97302</li>
                    <li>(305) 555-4446</li>
                    <li>(305) 555-4555</li>
                    <li>youremail@gmail.com</li>
                    <li>WP RESIDENCE</li>
                </ul>
            </div>

            @include('partials.layout.sidebar')

            <div>
                <h3 class="text-xl font-semibold tracking-[-0.01em] sm:text-2xl">Latest Properties</h3>
                <div class="mt-5 space-y-3.5">
                    <article class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=240&q=80" alt="Luxury House in Greenville" class="h-14 w-16 rounded-md object-cover sm:h-16 sm:w-20">
                        <div>
                            <p class="text-sm font-medium text-white sm:text-[15px]">Luxury House in Greenville</p>
                            <p class="text-sm text-slate-300 sm:text-[15px]">$860,000</p>
                        </div>
                    </article>
                    <article class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=240&q=80" alt="Modern Condo for Sale" class="h-14 w-16 rounded-md object-cover sm:h-16 sm:w-20">
                        <div>
                            <p class="text-sm font-medium text-white sm:text-[15px]">Modern Condo for Sale</p>
                            <p class="text-sm text-slate-300 sm:text-[15px]">$150,000</p>
                        </div>
                    </article>
                    <article class="flex items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=240&q=80" alt="Apartment with Subunits" class="h-14 w-16 rounded-md object-cover sm:h-16 sm:w-20">
                        <div>
                            <p class="text-sm font-medium text-white sm:text-[15px]">Apartment with Subunits</p>
                            <p class="text-sm text-slate-300 sm:text-[15px]">$999 / month</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col items-start justify-between gap-3 text-xs text-slate-400 sm:text-sm md:flex-row md:items-center">
            <p>Copyright WP Estate. All Rights Reserved.</p>
            <p>Documentation Video Tutorial Client Support</p>
        </div>
    </div>
</footer>
