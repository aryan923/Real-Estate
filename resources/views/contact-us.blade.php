@extends('layouts.master')

@section('title', 'Contact Us')

@section('content')
    <header class="relative isolate min-h-[500px] bg-[#08192f] z-30" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>

        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=2200&q=80" alt="Real estate office building" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(10,25,45,0.82)_0%,rgba(8,31,58,0.76)_45%,rgba(7,31,57,0.72)_100%)]"></div>

        <div class="section-shell relative z-10 flex min-h-[360px] flex-col items-center justify-center text-center sm:min-h-[400px]">
            <h1 class="heading-display text-[2.6rem] font-extrabold text-white sm:text-[3.7rem]">Contact Us</h1>
            <p class="mt-3 max-w-2xl text-[1rem] font-medium text-white/90 sm:text-[1.15rem]">Connect with our real estate specialists for buying, selling, rentals, and investments.</p>
        </div>
    </header>

    <section class="bg-[#f3f5f7] py-4">
        <div class="section-shell">
            <div class="flex flex-wrap items-center gap-2 text-[13px] font-medium text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-[#1e73be]">Home</a>
                <span>›</span>
                <span class="text-[#1e73be]">Contact Us</span>
            </div>
        </div>
    </section>

    <section class="bg-[#f3f5f7] py-14 sm:py-16">
        <div class="section-shell">
            <div class="mx-auto max-w-5xl text-center">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-copy mx-auto mt-4 max-w-3xl">
                    We are here to answer your questions and help you make confident real estate decisions.
                    Send us a message and our team will get back to you quickly.
                </p>
            </div>

            <div class="mx-auto mt-10 grid max-w-6xl gap-5 md:grid-cols-3">
                <article class="rounded bg-white p-6 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="mx-auto inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#e9f3ff] text-[#1e73be]">☎</div>
                    <h3 class="property-card-title mt-4">Call Us</h3>
                    <p class="property-card-copy mt-3">+1 408 167 1234</p>
                    <p class="property-card-copy mt-1">+1 800 555 6789</p>
                </article>

                <article class="rounded bg-white p-6 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="mx-auto inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#e9f3ff] text-[#1e73be]">✉</div>
                    <h3 class="property-card-title mt-4">Email</h3>
                    <p class="property-card-copy mt-3">contact@mail.com</p>
                    <p class="property-card-copy mt-1">support@realscape.com</p>
                </article>

                <article class="rounded bg-white p-6 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="mx-auto inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#e9f3ff] text-[#1e73be]">⌂</div>
                    <h3 class="property-card-title mt-4">Office Address</h3>
                    <p class="property-card-copy mt-3">1234 Beacon Street, Suite 101</p>
                    <p class="property-card-copy mt-1">Boston, MA 02118</p>
                </article>
            </div>

            <div class="mx-auto mt-10 grid max-w-6xl overflow-hidden rounded bg-white shadow-sm ring-1 ring-slate-200 lg:grid-cols-[1.1fr_1fr]">
                <div class="min-h-[460px]">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80" alt="Client consultation in office" class="h-full w-full object-cover">
                </div>

                <div class="p-6 sm:p-8">
                    <h3 class="section-title">Send Us A Message</h3>
                    <p class="section-copy mt-3">Tell us what you are looking for and we will match you with the right advisor.</p>

                    <form class="mt-6 space-y-3">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <input type="text" placeholder="First Name" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                            <input type="text" placeholder="Last Name" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                            <input type="email" placeholder="Email" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                            <input type="tel" placeholder="Phone" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                        </div>

                        <select class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                            <option>Select Inquiry Type</option>
                            <option>Buy a property</option>
                            <option>Sell a property</option>
                            <option>Rent a property</option>
                            <option>Investment consultation</option>
                        </select>

                        <textarea rows="5" placeholder="Message" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"></textarea>

                        <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded bg-[#1e73be] px-7 text-[14px] font-semibold text-white hover:bg-[#0f6fcb]">Send Message</button>
                    </form>
                </div>
            </div>

            <div class="mx-auto mt-10 max-w-6xl overflow-hidden rounded bg-white shadow-sm ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-6 py-4 sm:px-7">
                    <h3 class="section-title">Find Us On The Map</h3>
                    <p class="section-copy mt-2">Visit our office at 1234 Beacon Street, Suite 101, Boston, MA 02118.</p>
                </div>
                <iframe
                    title="RealScape Office Location"
                    class="h-[360px] w-full border-0 sm:h-[420px]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=1234+Beacon+Street,+Boston,+MA+02118&output=embed">
                </iframe>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
