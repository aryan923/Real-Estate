@extends('layouts.master')

@section('title', 'About Us')

@section('content')
    <header class="relative isolate min-h-[520px] overflow-hidden bg-[#08192f]" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>

        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=2200&q=80" alt="Team members" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(16,24,36,0.78)_0%,rgba(20,34,49,0.72)_45%,rgba(24,36,48,0.7)_100%)]"></div>

        <div class="section-shell relative z-10 flex min-h-[390px] flex-col items-center justify-center pt-6 text-center sm:min-h-[420px]">
            <h1 class="text-[46px] font-medium leading-tight text-white sm:text-[58px]">More about us</h1>
            <p class="mt-2 text-[18px] text-white/95 sm:text-[22px]">Get to know our talented team</p>
        </div>
    </header>

    <section class="bg-[#f3f3f3] py-18 sm:py-22">
        <div class="section-shell">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="section-title">More About Us</h2>
                <p class="section-copy mx-auto mt-5 max-w-3xl">
                    Utilizing her exceptional experience and knowledge of the luxury waterfront markets, Simone
                    serves an extensive and elite worldwide client base.
                </p>
            </div>

            <div class="mx-auto mt-12 grid max-w-5xl gap-x-16 gap-y-12 md:grid-cols-2">
                <article>
                    <h3 class="section-title">Our Mission</h3>
                    <p class="section-copy mt-4">
                        With over $3.5 Billion in sales, <span class="font-semibold">The Waterfront Realty Group</span> is the industry's
                        top luxury producer, with over 30 years of experience in marketing Seattle's
                        most prestigious waterfront properties.
                    </p>
                </article>

                <article>
                    <h3 class="section-title">Our Vision</h3>
                    <p class="section-copy mt-4">
                        Due to our unmatched results, expertise, and dedication, <span class="font-semibold">The Waterfront</span>
                        <span class="font-semibold">Realty Group</span> ranks among the Top 3 agencies in Seattle and the
                        surrounding area. We aim to stay the best for always.
                    </p>
                </article>

                <article>
                    <h3 class="section-title">Our Values</h3>
                    <p class="section-copy mt-4">
                        With her years of experience, impressive property portfolio, celebrity
                        clientele, and unparalleled knowledge of the market and pedigree estates,
                        Simone estimable business is sophisticated and renowned.
                    </p>
                </article>

                <article>
                    <h3 class="section-title">Our Resources</h3>
                    <p class="section-copy mt-4">
                        With her years of experience, impressive property portfolio, celebrity
                        clientele, and unparalleled knowledge of the market and pedigree estates,
                        Simone estimable business is sophisticated and renowned.
                    </p>
                </article>
            </div>
        </div>
    </section>

    @php
        $team = [
            [
                'name' => 'Mike Rutter',
                'member_id' => '987654321',
                'bio' => 'Mike Rutter is a highly skilled and personable real estate agent with a strong reputation ...',
                'image' => 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=900&q=80',
                'listings' => 4,
            ],
            [
                'name' => 'Janet Richmond',
                'member_id' => '987654321',
                'bio' => 'Janet Richmond is a dedicated and seasoned real estate agent known for her exceptional mar ...',
                'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=900&q=80',
                'listings' => 2,
            ],
            [
                'name' => 'Maria Barlow',
                'member_id' => '987654321',
                'bio' => 'Maria Barlow is a highly experienced and client-focused real estate agent known for her de ...',
                'image' => 'https://images.unsplash.com/photo-1544725176-7c40e5a71c5e?auto=format&fit=crop&w=900&q=80',
                'listings' => 3,
            ],
        ];
    @endphp

    <section class="bg-[#f3f3f3] pb-20">
        <div class="section-shell">
            <div class="mx-auto max-w-5xl text-center">
                <h2 class="section-title">Meet Our Team</h2>
                <p class="section-copy mx-auto mt-5 max-w-3xl">
                    If you want the best care possible for your real estate needs, our certified
                    professionals are here to help
                </p>
            </div>

            <div class="mx-auto mt-10 grid max-w-5xl gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($team as $member)
                    <article class="rounded bg-white p-3 shadow-[0_1px_2px_rgba(15,23,42,0.08)] ring-1 ring-slate-200">
                        <div class="relative aspect-[4/2.5] overflow-hidden rounded bg-[#d8d8dc]">
                            <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="h-full w-full object-cover object-center">
                            <span class="absolute bottom-3 left-3 rounded bg-[#59be74] px-2.5 py-1 text-[12px] font-medium leading-none text-white">{{ $member['listings'] }} listings</span>
                        </div>

                        <div class="px-2 pb-2 pt-4 text-left">
                            <h3 class="property-card-title">{{ $member['name'] }}</h3>
                            <p class="property-card-meta mt-1">Member ID: {{ $member['member_id'] }}</p>
                            <p class="property-card-copy mt-3">{{ $member['bio'] }}</p>

                            <div class="property-card-meta mt-5 flex items-center justify-between text-[#667a8c]">
                                <div class="flex items-center gap-2.5 text-[14px]">
                                    <a href="#" aria-label="Facebook" class="hover:text-[#1e73be]">f</a>
                                    <a href="#" aria-label="X" class="hover:text-[#1e73be]">x</a>
                                    <a href="#" aria-label="LinkedIn" class="hover:text-[#1e73be]">in</a>
                                    <a href="#" aria-label="Pinterest" class="hover:text-[#1e73be]">p</a>
                                    <a href="#" aria-label="Instagram" class="hover:text-[#1e73be]">o</a>
                                </div>
                                <div class="flex items-center gap-2.5 text-[14px]">
                                    <a href="#" aria-label="Phone" class="hover:text-[#1e73be]">☎</a>
                                    <a href="#" aria-label="Email" class="hover:text-[#1e73be]">✉</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.home.sections.testimonials')

    <section class="bg-[#f3f3f3] py-18 sm:py-22" id="faq">
        <div class="section-shell">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-copy mx-auto mt-5 max-w-3xl">
                    You can use this guide to familiarize yourself with rules, laws and other important
                    information relating to your property.
                </p>
            </div>

            <div class="mx-auto mt-10 grid max-w-5xl items-start gap-5 md:grid-cols-2" data-about-faq>
                <details class="group rounded bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.08)]" data-about-faq-item>
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-[16px] font-semibold leading-7 text-[#252a31]">
                        <span>Why is it considered necessary to register Agreement for Sale?</span>
                        <span class="mt-1 text-[14px] text-[#1f2a44] transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-3 text-[15px] leading-7 text-[#5f646c]">Registration gives legal validity and protects both buyer and seller rights in property transactions.</p>
                </details>

                <details class="group rounded bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.08)]" data-about-faq-item>
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-[16px] font-semibold leading-7 text-[#252a31]">
                        <span>Do I need to pay stamp duty if the property is transferred or is a gift?</span>
                        <span class="mt-1 text-[14px] text-[#1f2a44] transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-3 text-[15px] leading-7 text-[#5f646c]">Yes, stamp duty requirements vary by region and type of transfer, including gifts in many jurisdictions.</p>
                </details>

                <details class="group rounded bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.08)]" data-about-faq-item>
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-[16px] font-semibold leading-7 text-[#252a31]">
                        <span>What is Carpet Area?</span>
                        <span class="mt-1 text-[14px] text-[#1f2a44] transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-3 text-[15px] leading-7 text-[#5f646c]">Carpet area is the net usable floor area inside the apartment walls, excluding common spaces and walls.</p>
                </details>

                <details class="group rounded bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.08)]" data-about-faq-item>
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-[16px] font-semibold leading-7 text-[#252a31]">
                        <span>What are the documents a buyer would need from me?</span>
                        <span class="mt-1 text-[14px] text-[#1f2a44] transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-3 text-[15px] leading-7 text-[#5f646c]">Typically title deed, tax receipts, occupancy certificate, approved plans, and identity documents are required.</p>
                </details>

                <details class="group rounded bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.08)]" data-about-faq-item>
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-[16px] font-semibold leading-7 text-[#252a31]">
                        <span>How can I qualify for exemptions on Tax?</span>
                        <span class="mt-1 text-[14px] text-[#1f2a44] transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-3 text-[15px] leading-7 text-[#5f646c]">Tax exemptions depend on applicable laws, property use, and documentation proving eligibility.</p>
                </details>

                <details class="group rounded bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.08)]" data-about-faq-item>
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-[16px] font-semibold leading-7 text-[#252a31]">
                        <span>How soon would I receive a call from you after writing?</span>
                        <span class="mt-1 text-[14px] text-[#1f2a44] transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-3 text-[15px] leading-7 text-[#5f646c]">Our team generally responds to inquiries within one business day, often much sooner.</p>
                </details>
            </div>
        </div>
    </section>

    <section class="bg-[#f3f3f3] pb-24">
        <div class="section-shell">
            <div class="mx-auto max-w-5xl text-center">
                <h2 class="section-title">Our Team Stats</h2>
                <p class="section-copy mx-auto mt-4 max-w-3xl">
                    Despite well over $1 billion in combined sales, however, the team strives to maintain an
                    air of humility and discretion
                </p>

                <div class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-8 text-center sm:grid-cols-3">
                    <div>
                        <p class="text-[38px] font-semibold leading-none text-[#1e73be]">$100M</p>
                        <p class="property-card-meta mt-2 font-semibold text-[#252a31]">Current Listing Volume</p>
                    </div>
                    <div>
                        <p class="text-[38px] font-semibold leading-none text-[#1e73be]">$400M</p>
                        <p class="property-card-meta mt-2 font-semibold text-[#252a31]">Total Sold 2020 - 2022</p>
                    </div>
                    <div>
                        <p class="text-[38px] font-semibold leading-none text-[#1e73be]">$2B</p>
                        <p class="property-card-meta mt-2 font-semibold text-[#252a31]">Lifetime Sales Volume</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-12 grid max-w-4xl overflow-hidden rounded bg-white shadow-[0_1px_2px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 md:grid-cols-2">
                <div class="min-h-[430px]">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1000&q=80" alt="Modern building" class="h-full w-full object-cover">
                </div>

                <div class="p-7 sm:p-8">
                    <h3 class="section-title max-w-xs">Get in touch to plan your next transaction</h3>
                    <p class="section-copy mt-4">
                        Our experts and developers would love to contribute their expertise and insights and help you today.
                    </p>

                    <form class="mt-6 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Last name*</label>
                                <input type="text" placeholder="Last name" class="w-full rounded border border-slate-200 bg-[#f7f7f8] px-3 py-2 text-[14px] text-[#252a31] outline-none focus:border-[#1e73be]">
                            </div>
                            <div>
                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">First name*</label>
                                <input type="text" placeholder="First name" class="w-full rounded border border-slate-200 bg-[#f7f7f8] px-3 py-2 text-[14px] text-[#252a31] outline-none focus:border-[#1e73be]">
                            </div>
                            <div>
                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Email*</label>
                                <input type="email" placeholder="Email" class="w-full rounded border border-slate-200 bg-[#f7f7f8] px-3 py-2 text-[14px] text-[#252a31] outline-none focus:border-[#1e73be]">
                            </div>
                            <div>
                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Mobile</label>
                                <input type="text" placeholder="Mobile" class="w-full rounded border border-slate-200 bg-[#f7f7f8] px-3 py-2 text-[14px] text-[#252a31] outline-none focus:border-[#1e73be]">
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Message</label>
                            <textarea rows="4" placeholder="Message" class="w-full rounded border border-slate-200 bg-[#f7f7f8] px-3 py-2 text-[14px] text-[#252a31] outline-none focus:border-[#1e73be]"></textarea>
                        </div>

                        <button type="submit" class="inline-flex h-10 min-w-[150px] items-center justify-center rounded bg-[#1e73be] px-6 text-[14px] font-semibold text-white hover:bg-[#0f6fcb]">Send Email</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
