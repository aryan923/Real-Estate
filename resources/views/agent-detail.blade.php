@extends('layouts.master')

@section('title', $agent['name'])

@section('content')
    <header class="relative isolate bg-[#08192f] z-30" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>

        <div class="absolute inset-0 opacity-40" style="background-image: repeating-radial-gradient(circle at 12% 30%, transparent 0, transparent 22px, rgba(80, 124, 180, 0.32) 23px, transparent 24px), repeating-radial-gradient(circle at 70% 65%, transparent 0, transparent 26px, rgba(80, 124, 180, 0.24) 27px, transparent 28px);"></div>
        <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(6,20,39,0.92)_0%,rgba(8,29,55,0.88)_45%,rgba(8,31,59,0.9)_100%)]"></div>

        <div class="section-shell relative z-10 py-10 sm:py-12"></div>
    </header>

    <section class="bg-[#f3f5f7] py-3">
        <div class="section-shell">
            <div class="flex flex-wrap items-center gap-2 text-[13px] font-medium text-slate-600">
                <a href="{{ route('home') }}" class="hover:text-[#1e73be]">Home</a>
                <span>&rsaquo;</span>
                <span class="text-[#1e73be]">{{ $agent['name'] }}</span>
            </div>
        </div>
    </section>

    <section class="bg-[#ececec] pb-16 pt-6 sm:pt-8">
        <div class="section-shell relative">
            <div class="mx-auto max-w-5xl bg-[#f8f8f8] p-3 shadow-[0_1px_2px_rgba(15,23,42,0.12)] ring-1 ring-slate-200 sm:p-4">
                <div class="grid gap-5 md:grid-cols-[280px_minmax(0,1fr)] lg:grid-cols-[320px_minmax(0,1fr)]">
                    <div class="overflow-hidden bg-[#d6d6d6]">
                        <img src="{{ $agent['image'] }}" alt="{{ $agent['name'] }}" class="h-[320px] w-full object-cover object-center lg:h-[340px]">
                    </div>

                    <div class="px-1 py-2 sm:px-2">
                        <div class="flex items-center gap-1 text-[#f2b24a]">
                            @for ($i = 0; $i < 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                            @endfor
                            <span class="ml-1 text-[14px] font-medium text-slate-600">{{ $agent['reviews'] }} Reviews</span>
                        </div>

                        <h1 class="mt-2 text-[34px] font-semibold leading-[1.08] tracking-[-0.02em] text-[#1f2a44] sm:text-[40px]">{{ $agent['name'] }}</h1>
                        <p class="mt-2 text-[17px] text-[#425e74]">{{ $agent['role'] }} , {{ $agent['company'] }}</p>

                        <p class="mt-5 text-[16px] text-[#425e74]"><span class="font-semibold">Member of:</span> {{ $agent['member_of'] }}</p>
                        <p class="mt-3 text-[16px] leading-7 text-[#425e74]">{{ $agent['address'] }}</p>

                        <div class="mt-5 flex flex-wrap items-center gap-4 text-[#667a8c]">
                            <a href="#" aria-label="Facebook" class="hover:text-[#1e73be]" title="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.6 1.6-1.6h1.7V3.8c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4.1V10H8v3h2.6v8h2.9Z"/></svg>
                            </a>
                            <a href="#" aria-label="X" class="hover:text-[#1e73be]" title="X">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="m18.3 3.9-4.5 5.2 5.4 7h-4.2l-3.6-4.7-4.1 4.7H3.1l4.8-5.5L2.8 3.9H7l3.3 4.3 3.8-4.3h4.2Z"/></svg>
                            </a>
                            <a href="#" aria-label="LinkedIn" class="hover:text-[#1e73be]" title="LinkedIn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M6.9 8.8H3.8V20h3.1V8.8Zm.2-3.4a1.8 1.8 0 1 0-3.6 0 1.8 1.8 0 0 0 3.6 0ZM20.2 13.1c0-3.1-1.6-4.5-3.8-4.5-1.7 0-2.5 1-2.9 1.7h-.1V8.8h-3V20h3v-6.2c0-1.6.3-3.2 2.3-3.2s2 1.8 2 3.3V20h3V13Z"/></svg>
                            </a>
                            <a href="#" aria-label="Pinterest" class="hover:text-[#1e73be]" title="Pinterest">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M12 3.5c-4.5 0-6.8 3.2-6.8 6 0 2.3 1.3 3.5 2.1 3.5.3 0 .5-.9.5-1.1 0-.3-.8-1-8-2.3 0-1.5 1.2-4.4 4.9-4.4 2.7 0 4.7 1.6 4.7 4.3 0 2-1 5.8-3.6 5.8-1.1 0-2-1-2-2.2 0-1.9 1.3-3.7 1.3-5.1 0-3-1.1 1.1-1.1 1.8 0 .8.1 1.1.4 1.8-.5 2.2-1.5 5.4-.9 7.7l.1.3c.2.6.8.8 1.3.4.8-.7 1.5-2.1 1.9-3.2.6.6 1.6 1 2.7 1 3.9 0 6.3-3.8 6.3-8.3 0-3.9-3.3-6.3-7-6.3Z"/></svg>
                            </a>
                            <a href="#" aria-label="Instagram" class="hover:text-[#1e73be]" title="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 8.2a3.2 3.2 0 1 1 0-6.4 3.2 3.2 0 0 1 0 6.4Zm6.4-8.3a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0ZM22 12c0-2.1 0-2.4-.1-3.3-.1-.9-.3-1.6-.7-2.3a4.6 4.6 0 0 0-2-2c-.7-.4-1.4-.6-2.3-.7C16 3.6 15.7 3.6 13.6 3.6h-3.2c-2.1 0-2.4 0-3.3.1-.9.1-1.6.3-2.3.7a4.6 4.6 0 0 0-2 2c-.4.7-.6 1.4-.7 2.3C2 9.6 2 9.9 2 12v.1c0 2.1 0 2.4.1 3.3.1.9.3 1.6.7 2.3a4.6 4.6 0 0 0 2 2c.7.4 1.4.6 2.3.7.9.1 1.2.1 3.3.1h3.2c2.1 0 2.4 0 3.3-.1.9-.1 1.6-.3 2.3-.7a4.6 4.6 0 0 0 2-2c.4-.7.6-1.4.7-2.3.1-.9.1-1.2.1-3.3V12Zm-1.8.1c0 2 0 2.2-.1 3.1-.1.8-.2 1.2-.4 1.6-.3.6-.6.9-1.2 1.2-.4.2-.8.4-1.6.4-.9.1-1.1.1-3.1.1h-3.1c-2 0-2.2 0-3.1-.1-.8-.1-1.2-.2-1.6-.4a2.8 2.8 0 0 1-1.2-1.2c-.2-.4-.4-.8-.4-1.6-.1-.9-.1-1.1-.1-3.1V12c0-2 0-2.2.1-3.1.1-.8.2-1.2.4-1.6.3-.6.6-.9 1.2-1.2.4-.2.8-.4 1.6-.4.9-.1 1.1-.1 3.1-.1h3.1c2 0 2.2 0 3.1.1.8.1 1.2.2 1.6.4.6.3.9.6 1.2 1.2.2.4.4.8.4 1.6.1.9.1 1.1.1 3.1v.1Z"/></svg>
                            </a>
                            <a href="#" aria-label="YouTube" class="hover:text-[#1e73be]" title="YouTube">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M21.5 8.3a2.9 2.9 0 0 0-2-2c-1.7-.5-7.5-.5-7.5-.5s-5.8 0-7.5.5a2.9 2.9 0 0 0-2 2A30 30 0 0 0 2 12a30 30 0 0 0 .5 3.7 2.9 2.9 0 0 0 2 2c1.7.5 7.5.5 7.5.5s5.8 0 7.5-.5a2.9 2.9 0 0 0 2-2A30 30 0 0 0 22 12a30 30 0 0 0-.5-3.7ZM10 15.2V8.8l5.5 3.2-5.5 3.2Z"/></svg>
                            </a>
                            <a href="#" aria-label="Vimeo" class="hover:text-[#1e73be]" title="Vimeo">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M21.6 7.2c-.1 2.8-2 6.6-5.8 11.1-3.8 4.7-7 7-9.6 7-1.6 0-2.9-1.4-4-4.3l-2.2-8c-.3-1-.7-1.5-1.1-1.5-.1 0-.7.3-1.6.9L-4 10.7c1.2-1.1 2.4-2.2 3.5-3.3C1 6.1 2.2 5.3 3 5.2c2.1-.2 3.4 1.2 3.9 4.2.5 3.2.9 5.1 1 5.8.6 2.5 1.3 3.7 2 3.7.6 0 1.5-.9 2.7-2.8 1.2-1.8 1.9-3.2 2-4.2.2-1.6-.5-2.3-2-2.3-.7 0-1.4.2-2.2.5C11.8 5.8 13.7 3.8 16.1 4c3.5.1 5.1 1.2 5.5 3.2Z"/></svg>
                            </a>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center gap-2.5">
                            <a href="#" class="inline-flex min-h-10 items-center justify-center rounded bg-[#1e73be] px-10 text-[14px] font-semibold text-white hover:bg-[#0f6fcb]">Send</a>
                            <a href="tel:{{ $agent['phone'] }}" class="inline-flex min-h-10 items-center justify-center gap-1.5 rounded border border-[#1e73be] bg-white px-7 text-[14px] font-semibold text-[#1e73be] hover:bg-[#eff7ff]">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.3 1 .3 2 .4 3.1.4.7 0 1.2.5 1.2 1.2V20c0 .7-.5 1.2-1.2 1.2C10.5 21.2 2.8 13.5 2.8 4.2 2.8 3.5 3.3 3 4 3h3.5c.7 0 1.2.5 1.2 1.2 0 1 .1 2.1.4 3.1.1.4 0 .8-.3 1.2l-2.2 2.3Z"/></svg>
                                </span>
                                <span>Call {{ $agent['phone'] }}</span>
                            </a>
                            <a href="#" class="inline-flex min-h-10 items-center justify-center gap-1.5 rounded border border-[#1e73be] bg-white px-7 text-[14px] font-semibold text-[#1e73be] hover:bg-[#eff7ff]">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M20.5 3.5H3.5A2.5 2.5 0 0 0 1 6v12a2.5 2.5 0 0 0 2.5 2.5h17A2.5 2.5 0 0 0 23 18V6a2.5 2.5 0 0 0-2.5-2.5Zm-8.4 14.8c-1.1 0-2.2-.3-3.1-.9l-3.1.8.8-3c-.7-1-1.1-2.2-1.1-3.4 0-3.4 2.8-6.2 6.2-6.2s6.2 2.8 6.2 6.2-2.8 6.5-5.9 6.5Zm3.7-4.8c-.2-.1-1.3-.7-1.5-.7-.2-.1-.4-.1-.6.1s-.7.7-.8.8c-.1.2-.3.2-.5.1-1.4-.7-2.4-1.9-2.6-2.2-.1-.2 0-.3.1-.5.1-.1.2-.3.3-.4.1-.1.1-.2.2-.4s0-.3 0-.4c0-.1-.6-1.4-.8-1.9-.2-.4-.3-.4-.5-.4h-.4c-.1 0-.4 0-.6.2s-.8.8-.8 1.9 1 2.1 1.2 2.3c.1.1 1.9 3 4.6 4.1.6.3 1.1.4 1.5.5.6.2 1.1.2 1.5.1.5-.1 1.3-.5 1.5-1 .2-.5.2-1 .1-1.1 0-.1-.2-.1-.4-.2Z"/></svg>
                                </span>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#ececec] pb-16">
        <div class="section-shell">
            <div class="mx-auto max-w-5xl">
                <div class="grid gap-6 lg:grid-cols-2">
                    <article class="bg-[#f8f8f8] p-6 shadow-[0_1px_2px_rgba(15,23,42,0.1)] ring-1 ring-slate-200 sm:p-7">
                        <h2 class="text-[28px] font-semibold leading-[1.18] tracking-[-0.02em] text-[#1f2a44]">About Me</h2>
                        <p class="mt-4 text-[14px] leading-7 text-[#425e74]">
                            With a genuine passion for helping clients find their dream homes, {{ $agent['name'] }} brings a unique blend of market insight, dedication, and a client-centered approach to every real estate transaction. Known for his integrity, professionalism, and skilled negotiation, {{ $agent['name'] }} ensures that buyers and sellers feel confident and supported throughout the entire process.
                        </p>

                        <h3 class="mt-7 text-[17px] font-semibold text-[#1f2a44]">Specialties &amp; Service Areas</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full border border-[#bad0ea] bg-[#d8e9fb] px-4 py-1.5 text-[13px] font-medium text-[#1e73be]">New York</span>
                            <span class="rounded-full border border-[#bad0ea] bg-[#d8e9fb] px-4 py-1.5 text-[13px] font-medium text-[#1e73be]">Commercial</span>
                            <span class="rounded-full border border-[#bad0ea] bg-[#d8e9fb] px-4 py-1.5 text-[13px] font-medium text-[#1e73be]">Consultancy</span>
                            <span class="rounded-full border border-[#bad0ea] bg-[#d8e9fb] px-4 py-1.5 text-[13px] font-medium text-[#1e73be]">Residential</span>
                        </div>

                        <div class="mt-6 grid gap-x-6 gap-y-4 sm:grid-cols-3">
                            <div>
                                <p class="text-[15px] font-semibold text-[#1f2a44]">Experience</p>
                                <p class="mt-1 text-[14px] leading-7 text-[#425e74]">18 years as Expert Realtor at Future Realty</p>
                            </div>
                            <div>
                                <p class="text-[15px] font-semibold text-[#1f2a44]">When you can reach me</p>
                                <p class="mt-1 text-[14px] leading-7 text-[#425e74]">Daily, from 9 AM to 5 PM at our local offices</p>
                            </div>
                            <div>
                                <p class="text-[15px] font-semibold text-[#1f2a44]">Personal awards</p>
                                <p class="mt-1 text-[14px] leading-7 text-[#425e74]">Best Realtor from 2000-2024</p>
                            </div>
                            <div>
                                <p class="text-[15px] font-semibold text-[#1f2a44]">MLS ID</p>
                                <p class="mt-1 text-[14px] leading-7 text-[#425e74]">126775 MIYD - Until 12/12/2025</p>
                            </div>
                            <div>
                                <p class="text-[15px] font-semibold text-[#1f2a44]">I speak</p>
                                <p class="mt-1 text-[14px] leading-7 text-[#425e74]">French, Spanish, English</p>
                            </div>
                            <div>
                                <p class="text-[15px] font-semibold text-[#1f2a44]">Experience</p>
                                <p class="mt-1 text-[14px] leading-7 text-[#425e74]">30 Years as Realtor</p>
                            </div>
                        </div>
                    </article>

                    <article class="bg-[#f8f8f8] p-6 shadow-[0_1px_2px_rgba(15,23,42,0.1)] ring-1 ring-slate-200 sm:p-7">
                        <h2 class="text-[28px] font-semibold leading-[1.18] tracking-[-0.02em] text-[#1f2a44]">Contact Me</h2>

                        <div class="mt-5 space-y-3 text-[14px] text-[#425e74]">
                            <p class="inline-flex items-center gap-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.3 1 .3 2 .4 3.1.4.7 0 1.2.5 1.2 1.2V20c0 .7-.5 1.2-1.2 1.2C10.5 21.2 2.8 13.5 2.8 4.2 2.8 3.5 3.3 3 4 3h3.5c.7 0 1.2.5 1.2 1.2 0 1 .1 2.1.4 3.1.1.4 0 .8-.3 1.2l-2.2 2.3Z"/></svg>
                                </span>
                                <span>{{ $agent['phone'] }}</span>
                            </p>
                            <p class="inline-flex items-center gap-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M17 2a3 3 0 0 1 3 3v14a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h10Zm0 2H7a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1Zm-4.8 14.5h-.4a1 1 0 0 0 0 2h.4a1 1 0 0 0 0-2ZM16 6H8v10h8V6Z"/></svg>
                                </span>
                                <span>1-800-555-1111</span>
                            </p>
                            <p class="inline-flex items-center gap-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M3 6.8A1.8 1.8 0 0 1 4.8 5h14.4A1.8 1.8 0 0 1 21 6.8v10.4a1.8 1.8 0 0 1-1.8 1.8H4.8A1.8 1.8 0 0 1 3 17.2V6.8Zm1.8.2 7.2 5 7.2-5H4.8Zm14.4 10.2V9.3l-6.7 4.6a1 1 0 0 1-1.1 0L4.8 9.3v7.9h14.4Z"/></svg>
                                </span>
                                <span>jack_demo@gmail.com</span>
                            </p>
                            <p class="inline-flex items-center gap-2">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm7.9 9h-3.2a15.7 15.7 0 0 0-1.1-5 8 8 0 0 1 4.3 5ZM12 4c.9 0 2.4 2.1 2.8 7H9.2c.4-4.9 1.9-7 2.8-7ZM8.4 6a15.7 15.7 0 0 0-1.1 5H4.1a8 8 0 0 1 4.3-5Zm-4.3 7h3.2a15.7 15.7 0 0 0 1.1 5A8 8 0 0 1 4.1 13Zm7.9 7c-.9 0-2.4-2.1-2.8-7h5.6c-.4 4.9-1.9 7-2.8 7Zm3.6-2a15.7 15.7 0 0 0 1.1-5h3.2a8 8 0 0 1-4.3 5Z"/></svg>
                                </span>
                                <span>mywebsiteurl.com</span>
                            </p>
                        </div>

                        <form id="agent-contact-form" class="mt-6 space-y-3" novalidate>
                            @csrf
                            <input type="hidden" name="type" value="agent">
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <input type="text" name="name" placeholder="Name" required class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                    <span class="error-msg text-xs text-red-500 hidden mt-1">Name is required.</span>
                                </div>
                                <div>
                                    <input type="email" name="email" placeholder="Email" required class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                    <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter a valid email address.</span>
                                </div>
                                <div>
                                    <input type="tel" name="phone" placeholder="Phone" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                    <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter a valid phone number.</span>
                                </div>
                                <div>
                                    <select name="enq_for" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <option value="">What are you looking to do?</option>
                                        <option value="sale">Sale</option>
                                        <option value="rent">Rent</option>
                                        <option value="lease">Lease</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <textarea name="message" rows="5" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]" placeholder="Message"></textarea>
                            </div>

                            <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded bg-[#1e73be] px-10 text-[15px] font-semibold text-white hover:bg-[#0f6fcb] transition flex items-center justify-center gap-2">
                                <span class="submit-text">Send</span>
                                <svg class="spinner hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </form>
                    </article>
                </div>
            </div>
        </div>
    </section>

    @php
        $listings = [
            [
                'title' => 'Two Bedroom Apartment',
                'price' => '$300 / month',
                'excerpt' => 'In 1588, the Taiko of Japan, Toyotomi Hideyoshi, dispatched the Daimyo So Yoshitoshi on a ...',
                'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=900&q=80',
                'category' => 'apartments',
                'intent' => 'Rentals',
                'location' => 'Greenville, Jersey City',
                'beds' => 2,
                'baths' => 1,
                'size' => '190 ft²',
            ],
            [
                'title' => 'One Bedroom Apartment',
                'price' => '$120 / month',
                'excerpt' => 'Mckinley Hill is situated inside Fort Bonifacio which is a 50 hectares owned by Megaworld. ...',
                'image' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=900&q=80',
                'category' => 'apartments',
                'intent' => 'Rentals',
                'status' => 'Hot Offer',
                'location' => 'Bayonne, Jersey City',
                'beds' => 1,
                'baths' => 1,
                'size' => '200 ft²',
            ],
            [
                'title' => 'Apartment for rent in Jersey City',
                'price' => '$800 / month',
                'excerpt' => 'Beautiful, updated, ground level Co-op apartment in the desirable Bay Terrace neighborhood ...',
                'image' => 'https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?auto=format&fit=crop&w=900&q=80',
                'category' => 'apartments',
                'intent' => 'Rentals',
                'location' => 'The Heights, Jersey City',
                'beds' => 2,
                'baths' => 3,
                'size' => '600 ft²',
            ],
            [
                'title' => 'Luxury Condo in Downtown',
                'price' => '$560 / month',
                'excerpt' => 'Spacious corner condo with city skyline views, private balcony, and premium amenities ...',
                'image' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=900&q=80',
                'category' => 'condos',
                'intent' => 'Rentals',
                'location' => 'Newport, Jersey City',
                'beds' => 2,
                'baths' => 2,
                'size' => '420 ft²',
            ],
            [
                'title' => 'Modern Condo Loft',
                'price' => '$490 / month',
                'excerpt' => 'Contemporary loft condo with floor-to-ceiling windows and quick access to transit ...',
                'image' => 'https://images.unsplash.com/photo-1600607687644-aac4c3eac7f4?auto=format&fit=crop&w=900&q=80',
                'category' => 'condos',
                'intent' => 'Rentals',
                'location' => 'Paulus Hook, Jersey City',
                'beds' => 1,
                'baths' => 1,
                'size' => '280 ft²',
            ],
            [
                'title' => 'Creative Office Space',
                'price' => '$1,250 / month',
                'excerpt' => 'Flexible office floor with meeting rooms, reception area, and high-speed internet setup ...',
                'image' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=900&q=80',
                'category' => 'offices',
                'intent' => 'Commercial',
                'location' => 'Exchange Place, Jersey City',
                'beds' => 0,
                'baths' => 1,
                'size' => '900 ft²',
            ],
        ];

        $apartmentCount = collect($listings)->where('category', 'apartments')->count();
        $condoCount = collect($listings)->where('category', 'condos')->count();
        $officeCount = collect($listings)->where('category', 'offices')->count();
    @endphp

    <section class="bg-[#ececec] pb-20" data-agent-listings-section>
        <div class="section-shell">
            <div class="mx-auto max-w-5xl">
                <h2 class="text-[30px] font-semibold leading-[1.15] tracking-[-0.02em] text-[#1f2a44]">My Listings</h2>

                <div class="mt-4 flex flex-wrap gap-0.5 rounded bg-[#f3f3f3] p-0.5">
                    <button type="button" class="rounded px-6 py-2.5 text-[14px] font-semibold transition" data-agent-listings-tab="all">All ({{ count($listings) }})</button>
                    <button type="button" class="rounded px-6 py-2.5 text-[14px] font-semibold transition" data-agent-listings-tab="apartments">Apartments ({{ $apartmentCount }})</button>
                    <button type="button" class="rounded px-6 py-2.5 text-[14px] font-semibold transition" data-agent-listings-tab="condos">Condos ({{ $condoCount }})</button>
                    <button type="button" class="rounded px-6 py-2.5 text-[14px] font-semibold transition" data-agent-listings-tab="offices">Offices ({{ $officeCount }})</button>
                </div>

                <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3" data-agent-listings-grid>
                    @foreach ($listings as $listing)
                        <article class="property-listing-card overflow-hidden rounded bg-white shadow-[0_1px_2px_rgba(15,23,42,0.06)] ring-1 ring-slate-200" data-agent-listing-card data-listing-category="{{ $listing['category'] }}">
                            <div class="relative">
                                <img src="{{ $listing['image'] }}" alt="{{ $listing['title'] }}" class="h-48 w-full object-cover">
                                <div class="absolute right-3 top-3 flex gap-1">
                                    <span class="rounded bg-[#1e73be] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">{{ $listing['intent'] }}</span>
                                    @if (!empty($listing['status']))
                                        <span class="rounded bg-[#2f7ecc] px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">{{ $listing['status'] }}</span>
                                    @endif
                                </div>
                                <div class="absolute bottom-3 left-3 text-xs font-semibold text-white drop-shadow-sm">{{ $listing['location'] }}</div>
                                <div class="absolute bottom-3 right-3 text-xs font-semibold text-white drop-shadow-sm">{{ $listing['beds'] + $listing['baths'] }}</div>
                            </div>

                            <div class="p-4">
                                <h3 class="property-card-title"><a href="{{ route('properties') }}" class="hover:text-[#1e73be]">{{ $listing['title'] }}</a></h3>
                                <p class="property-card-price mt-2">{{ $listing['price'] }}</p>
                                <p class="property-card-copy mt-3">{{ $listing['excerpt'] }}</p>

                                <div class="property-card-meta mt-4 flex items-center gap-4 border-b border-slate-200 pb-3">
                                    <span>{{ $listing['beds'] }}</span>
                                    <span>{{ $listing['baths'] }}</span>
                                    <span>{{ $listing['size'] }}</span>
                                </div>

                                <div class="mt-3 flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $agent['image'] }}" alt="{{ $agent['name'] }}" class="h-7 w-7 rounded-full object-cover">
                                        <span class="property-card-agent">{{ $agent['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-400">
                                        <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Share">↗</button>
                                        <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Favorite">♡</button>
                                        <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded border border-slate-200 hover:text-slate-600" aria-label="Add">＋</button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-7 text-center">
                    <button type="button" class="inline-flex min-h-10 items-center justify-center rounded bg-[#1e73be] px-8 text-[14px] font-semibold text-white hover:bg-[#0f6fcb]" data-agent-load-more>Load More Listings</button>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#ececec] pb-20">
        <div class="section-shell">
            <div class="mx-auto max-w-5xl bg-[#f8f8f8] p-6 shadow-[0_1px_2px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 sm:p-7">
                <h2 class="text-[28px] font-semibold leading-[1.18] tracking-[-0.02em] text-[#1f2a44]">Property Reviews</h2>

                <div class="mt-4 flex flex-wrap items-center gap-2 text-[15px] text-[#1f2a44]">
                    <span class="font-semibold">1 Reviews</span>
                    <span class="inline-flex items-center gap-0.5 text-[#f2b24a]" aria-label="Five star rating">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                    </span>
                </div>

                <article class="mt-6 flex gap-4">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80" alt="Janet Richmond" class="h-12 w-12 rounded-full object-cover">

                    <div class="flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-[14px] font-semibold text-[#6c86a0]">Posted by Janet Richmond</p>
                                <p class="mt-1 text-[15px] font-semibold text-[#1f2a44]">Jack is a great agent!</p>
                            </div>

                            <div class="text-right">
                                <p class="inline-flex items-center gap-0.5 text-[#f2b24a]">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="m12 17.3-5.2 3 1-5.9-4.3-4.2 6-.9L12 4l2.5 5.3 6 .9-4.3 4.2 1 5.9L12 17.3Z"/></svg>
                                    <span class="ml-1 text-[15px] text-[#6c86a0]">(5 of 5)</span>
                                </p>
                                <p class="mt-1 text-[14px] text-[#6c86a0]">Posted on 15 July 2025</p>
                            </div>
                        </div>

                        <p class="mt-3 text-[14px] leading-8 text-[#425e74]">
                            We were referred to Jack through our close friends and are so happy with the service he provided! This was our first house, so we had a lot of questions and he was happy to answer them all! He also went to bat for us and guided us to make smart decisions when it came to the negotiations. I hope to not move again anytime soon, but when we do, we know who to call!
                        </p>
                    </div>
                </article>

                <div class="mt-6 rounded bg-[#f0f0f0] px-4 py-3 text-[14px] font-semibold text-[#1f2a44]">
                    You need to <a href="#" class="text-[#1e73be] hover:underline">login</a> in order to post a review
                </div>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module">
$(document).ready(function() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function clearErrors(form) {
        $(form).find('.error-msg').addClass('hidden');
        $(form).find('input, select, textarea').removeClass('border-red-500').addClass('border-slate-200');
    }

    function showFieldError(inputEl, errorText) {
        inputEl.addClass('border-red-500').removeClass('border-slate-200');
        const errorEl = inputEl.parent().find('.error-msg');
        if (errorEl.length) {
            if (errorText) {
                errorEl.text(errorText);
            }
            errorEl.removeClass('hidden');
        }
    }

    $('#agent-contact-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const $form = $(form);
        clearErrors(form);

        let isValid = true;

        const nameInput = $form.find('input[name="name"]');
        if (!nameInput.val().trim()) {
            showFieldError(nameInput, 'Name is required.');
            isValid = false;
        }

        const emailInput = $form.find('input[name="email"]');
        const emailVal = emailInput.val().trim();
        if (!emailVal) {
            showFieldError(emailInput, 'Email is required.');
            isValid = false;
        } else if (!emailRegex.test(emailVal)) {
            showFieldError(emailInput, 'Please enter a valid email address.');
            isValid = false;
        }

        const phoneInput = $form.find('input[name="phone"]');
        const phoneVal = phoneInput.val().trim();
        if (phoneVal && phoneVal.length > 20) {
            showFieldError(phoneInput, 'Phone number must be under 20 characters.');
            isValid = false;
        }

        if (!isValid) return;

        const submitBtn = $form.find('button[type="submit"]');
        const spinner = submitBtn.find('.spinner');
        const submitText = submitBtn.find('.submit-text');

        submitBtn.prop('disabled', true);
        spinner.removeClass('hidden');
        if (submitText.length) submitText.css('opacity', '0.5');

        $.ajax({
            url: "{{ route('save_enq') }}",
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                submitBtn.prop('disabled', false);
                spinner.addClass('hidden');
                if (submitText.length) submitText.css('opacity', '1');

                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message || 'Submitted successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e73be'
                    });
                    
                    // Reset inputs
                    $form.find('input[type="text"], input[type="email"], input[type="tel"]').val('');
                    $form.find('select').val('');
                    $form.find('textarea').val('');
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'Submission failed. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e73be'
                    });
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false);
                spinner.addClass('hidden');
                if (submitText.length) submitText.css('opacity', '1');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please correct the errors in the form.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e73be'
                    });

                    Object.keys(errors).forEach(function(field) {
                        const inputEl = $form.find(`[name="${field}"]`);
                        if (inputEl.length) {
                            showFieldError(inputEl, errors[field][0]);
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e73be'
                    });
                }
            }
        });
    });
});
</script>
@endpush
