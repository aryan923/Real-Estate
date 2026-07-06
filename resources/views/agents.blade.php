@extends('layouts.master')

@section('title', 'Agent List')

@section('content')
    <header class="relative isolate overflow-hidden bg-[#08192f]" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>

        <div class="absolute inset-0 opacity-40" style="background-image: repeating-radial-gradient(circle at 12% 30%, transparent 0, transparent 22px, rgba(80, 124, 180, 0.32) 23px, transparent 24px), repeating-radial-gradient(circle at 70% 65%, transparent 0, transparent 26px, rgba(80, 124, 180, 0.24) 27px, transparent 28px);"></div>
        <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(6,20,39,0.92)_0%,rgba(8,29,55,0.88)_45%,rgba(8,31,59,0.9)_100%)]"></div>

        <div class="section-shell relative z-10 flex min-h-[280px] flex-col items-center justify-center py-8 text-center sm:min-h-[320px] sm:py-10">
            <h1 class="text-[46px] font-extrabold leading-[1.05] tracking-[-0.02em] text-white sm:text-[56px]">Real Estate Agents</h1>
            <p class="mt-3 text-[16px] font-semibold text-white/90 sm:text-[17px]">
                a list with all registered agents
            </p>
        </div>
    </header>

    <section class="bg-[#f3f5f7] py-6">
        <div class="section-shell">
            <div class="flex flex-wrap items-center gap-2 text-[13px] font-medium text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-[#1e73be]">Home</a>
                <span>›</span>
                <span class="text-[#1e73be]">Agents List Page</span>
            </div>
        </div>
    </section>

    @php
        $agents = [
            [
                'name' => 'Amanda Smithson',
                'member_id' => '987654321',
                'bio' => 'Known for her personalized approach, Amanda combines her extensive knowledge of the local market with client-first guidance.',
                'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=900&q=80',
                'listings' => 4,
            ],
            [
                'name' => 'Samuel Diesel',
                'member_id' => '963852741',
                'bio' => 'With a genuine passion for helping clients find their dream homes, Samuel brings a strategic and data-driven approach.',
                'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=900&q=80',
                'listings' => 4,
            ],
            [
                'name' => 'Lily Bicharm',
                'member_id' => '951753486',
                'bio' => 'Lily specializes in matching first-time buyers with the right property while making the full process clear and stress-free.',
                'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&q=80',
                'listings' => 5,
            ],
            [
                'name' => 'Dennis Albo',
                'member_id' => '974125863',
                'bio' => 'Dennis helps investors evaluate opportunities through market trend analysis and practical long-term portfolio planning.',
                'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=900&q=80',
                'listings' => 3,
            ],
            [
                'name' => 'Maria Fields',
                'member_id' => '982364715',
                'bio' => 'Maria leads rental and leasing support for premium apartments and townhomes in high-demand neighborhoods.',
                'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=900&q=80',
                'listings' => 6,
            ],
            [
                'name' => 'Michael Sutther',
                'member_id' => '918273645',
                'bio' => 'Michael focuses on efficient closings, transparent communication, and accurate property valuation across city markets.',
                'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=900&q=80',
                'listings' => 2,
            ],
        ];
    @endphp

    <section class="bg-[#f3f5f7] pb-14">
        <div class="section-shell">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-start">
                <div>
                    <h2 class="section-title">Agents List Page</h2>
                    <p class="mt-4 max-w-4xl text-[15px] leading-8 text-slate-600">
                        We like to think of ourselves as a small but perfectly formed lettings and management agency. Working you get the exposure, knowledge and expertise you would expect from a large agent, but the service you will only receive from a smaller business built around 100% client and tenant focus.
                    </p>
                    <p class="mt-4 max-w-4xl text-[15px] leading-8 text-slate-600">
                        Whether you are looking for property for sale in New York area or property for rent, WP Residence makes searching easy. Use our unique geolocation mapping feature to root-out your ideal villa, townhouse or apartment and contact the owners direct. We will help you find your dream house in just a few seconds.
                    </p>

                    <div class="hide-scrollbar mt-6 max-h-[930px] overflow-y-auto pr-2">
                        <div class="grid gap-5 md:grid-cols-2">
                            @foreach ($agents as $agent)
                                <article class="rounded bg-white p-3 shadow-[0_1px_2px_rgba(15,23,42,0.08)] ring-1 ring-slate-200">
                                    <div class="relative aspect-[4/3] overflow-hidden rounded bg-[#f1f3f8]">
                                        <img src="{{ $agent['image'] }}" alt="{{ $agent['name'] }}" class="h-full w-full object-cover object-center">
                                        <span class="absolute bottom-3 left-3 rounded bg-[#59be74] px-2.5 py-1 text-[12px] font-medium leading-none text-white">{{ $agent['listings'] }} listings</span>
                                    </div>
                                    <div class="px-3 pb-3 pt-4">
                                        <h3 class="property-card-title"><a href="{{ route('agent-detail', ['slug' => \Illuminate\Support\Str::slug($agent['name'])]) }}" class="hover:text-[#1e73be]">{{ $agent['name'] }}</a></h3>
                                        <p class="property-card-meta mt-1">Member ID: {{ $agent['member_id'] }}</p>
                                        <p class="property-card-copy mt-3">{{ \Illuminate\Support\Str::limit($agent['bio'], 86, ' ...') }}</p>

                                        <div class="property-card-meta mt-5 flex items-center justify-between text-[#667a8c]">
                                            <div class="flex items-center gap-2.5">
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
                                            </div>
                                            <div class="flex items-center gap-2.5">
                                                <a href="#" aria-label="Phone" class="hover:text-[#1e73be]" title="Phone">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.3 1 .3 2 .4 3.1.4.7 0 1.2.5 1.2 1.2V20c0 .7-.5 1.2-1.2 1.2C10.5 21.2 2.8 13.5 2.8 4.2 2.8 3.5 3.3 3 4 3h3.5c.7 0 1.2.5 1.2 1.2 0 1 .1 2.1.4 3.1.1.4 0 .8-.3 1.2l-2.2 2.3Z"/></svg>
                                                </a>
                                                <a href="#" aria-label="Email" class="hover:text-[#1e73be]" title="Email">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M3 6.8A1.8 1.8 0 0 1 4.8 5h14.4A1.8 1.8 0 0 1 21 6.8v10.4a1.8 1.8 0 0 1-1.8 1.8H4.8A1.8 1.8 0 0 1 3 17.2V6.8Zm1.8.2 7.2 5 7.2-5H4.8Zm14.4 10.2V9.3l-6.7 4.6a1 1 0 0 1-1.1 0L4.8 9.3v7.9h14.4Z"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <nav class="mt-6 flex items-center gap-2" aria-label="Agent pagination">
                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-300 bg-white text-slate-500 hover:bg-slate-100" aria-label="Previous page">&lsaquo;</button>
                        <button type="button" class="inline-flex h-8 min-w-8 items-center justify-center rounded border border-[#1e73be] bg-[#1e73be] px-2 text-sm font-semibold text-white">1</button>
                        <button type="button" class="inline-flex h-8 min-w-8 items-center justify-center rounded border border-slate-300 bg-white px-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">2</button>
                        <button type="button" class="inline-flex h-8 min-w-8 items-center justify-center rounded border border-slate-300 bg-white px-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">3</button>
                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded border border-slate-300 bg-white text-slate-500 hover:bg-slate-100" aria-label="Next page">&rsaquo;</button>
                    </nav>
                </div>

                <aside class="lg:sticky lg:top-24">
                    <div class="rounded bg-white p-6 shadow-[0_1px_2px_rgba(15,23,42,0.06)] ring-1 ring-slate-200">
                        <h3 class="property-card-title">Search Widget for Agents, Agencies &amp; Developers</h3>

                        <form class="mt-5 space-y-3">
                            <input type="text" placeholder="Name" class="w-full rounded border border-slate-200 px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                            <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"><option>Agent</option></select>
                            <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"><option>Select City</option></select>
                            <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"><option>Select Area</option></select>
                            <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"><option>Select Category</option></select>
                            <select class="w-full rounded border border-slate-200 px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"><option>Select Action Category</option></select>

                            <button type="submit" class="w-full rounded bg-[#1279eb] px-4 py-2.5 text-[15px] font-semibold text-white hover:bg-[#0f6fcb]">Search</button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
