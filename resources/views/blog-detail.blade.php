@extends('layouts.master')

@section('title', $post['title'])

@section('content')
    <header class="relative isolate bg-[#08192f] z-30" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>
    </header>

    <section class="bg-[#f7f8fa] py-6 sm:py-8">
        <div class="section-shell">
            <div class="flex flex-wrap items-center gap-2 text-[13px] font-medium text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-[#1e73be]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('blog') }}" class="hover:text-[#1e73be]">{{ $post['category'] }}</a>
                <span>&rsaquo;</span>
                <span class="text-[#1e73be]">{{ $post['title'] }}</span>
            </div>
        </div>
    </section>

    <section id="post-content" class="bg-[#ececec] pb-16 pt-2 sm:pt-4">
        <div class="section-shell">
            @php
                $imageFallback = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80';

                $featuredListings = [
                    ['title' => 'Luxury House in Greenville', 'price' => '$860,000', 'image' => 'https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=240&q=80'],
                    ['title' => 'Modern Condo for Sale', 'price' => '$150,000', 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=240&q=80'],
                    ['title' => 'Apartment with Subunits', 'price' => '$999 / month', 'image' => 'https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=240&q=80'],
                    ['title' => 'Three Room Apartment', 'price' => '$550,000', 'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=240&q=80'],
                    ['title' => 'Suburb Home for Sale', 'price' => '$1,000,000', 'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=240&q=80'],
                ];

                $comments = [
                    ['name' => 'Gina Manny', 'date' => 'on March 16, 2021', 'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80', 'text' => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which do not look even slightly believable.'],
                    ['name' => 'Simone Butcher', 'date' => 'on March 16, 2021', 'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80', 'text' => 'Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure.'],
                    ['name' => 'Gina Manny', 'date' => 'on March 16, 2021', 'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80', 'text' => 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth.'],
                ];

                $similarPosts = [
                    ['title' => 'New York Real Estate', 'date' => 'May 27, 2014', 'excerpt' => 'To the north, the Harlem River divides Manhattan Island from Bronx and the mainland United States.', 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80'],
                    ['title' => 'Manhattan Apartments', 'date' => 'May 27, 2014', 'excerpt' => 'In Manhattan, uptown means north (more precisely north-northeast, which is the direction the island is oriented).', 'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1200&q=80'],
                    ['title' => 'Buying a Home', 'date' => 'May 27, 2014', 'excerpt' => 'A practical checklist to evaluate neighborhoods, schools, commute, and long-term value before closing.', 'image' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1200&q=80'],
                ];
            @endphp

            <div class="mx-auto grid max-w-5xl gap-6 md:grid-cols-[minmax(0,1fr)_280px] lg:grid-cols-[minmax(0,1fr)_300px] xl:grid-cols-[minmax(0,1fr)_320px] md:items-start">
                <article class="space-y-6">
                    <div class="overflow-hidden rounded-[4px] bg-white p-3 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-4">
                        <div class="relative overflow-hidden rounded-[2px] bg-[#d9d9d9]">
                            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-[300px] w-full object-cover sm:h-[420px]">
                            <button type="button" class="absolute left-2 top-1/2 inline-flex h-11 w-8 -translate-y-1/2 items-center justify-center rounded bg-black/35 text-white hover:bg-black/55">‹</button>
                            <button type="button" class="absolute right-2 top-1/2 inline-flex h-11 w-8 -translate-y-1/2 items-center justify-center rounded bg-black/35 text-white hover:bg-black/55">›</button>
                            <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 items-center gap-1.5">
                                <span class="h-3 w-3 rounded-full bg-[#1e73be]"></span>
                                <span class="h-3 w-3 rounded-full bg-white"></span>
                                <span class="h-3 w-3 rounded-full bg-white"></span>
                                <span class="h-3 w-3 rounded-full bg-white"></span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[4px] bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-8">
                        <h1 class="text-[40px] font-extrabold leading-[1.06] tracking-[-0.02em] text-[#242b34]">About Real Estate</h1>
                        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-[14px] text-[#4f6277]">
                            <span>Posted by admin on {{ $post['date'] }}</span>
                            <span>Buying Properties, Location, Price, Real Estate</span>
                            <span>3 Comments</span>
                        </div>

                        <div class="mt-7 space-y-5 text-[15px] leading-8 text-[#445b73]">
                            <p>New York County as a whole covers a total area of 33.77 square miles (87.5 km2), of which 22.96 square miles (59.5 km2) are land and 10.81 square miles (28.0 km2) are water.</p>
                            <blockquote class="border-l-2 border-[#2f7ecc] pl-5 italic text-[#3d5267]">A modern redrawing of the 1807 version of the Commissioner’s Grid plan for Manhattan, a few years before it was adopted in 1811. Central Park is absent.</blockquote>
                            <p>Duis mattis laoreet neque, et ornare neque sollicitudin at. Proin sagittis dolor sed mi elementum pretium. Donec et justo ante. Vivamus egestas sodales est, eu rhoncus urna semper eu.</p>
                        </div>

                        <h2 class="mt-6 text-[30px] font-extrabold leading-[1.1] tracking-[-0.02em] text-[#242b34]">What you must know about the New York market</h2>
                        <div class="mt-5 space-y-4 text-[15px] leading-8 text-[#445b73]">
                            <p>Curabitur massa magna, tempor in blandit id, porta in ligula. Aliquam laoreet nisl massa, at interdum mauris sollicitudin et. Mauris risus lectus, tristique at nisl at, pharetra tristique enim.</p>
                            <p>Nullam this is a link nibh facilisis, at malesuada orci congue. Nullam tempus sollicitudin cursus. Nulla elit mauris, volutpat eu varius malesuada, pulvinar eu ligula.</p>
                            <ul class="list-disc space-y-2 pl-5">
                                <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                                <li>Aliquam tincidunt mauris eu risus.</li>
                                <li>Vestibulum auctor dapibus neque.</li>
                            </ul>
                        </div>

                        <h2 class="mt-7 text-[26px] font-extrabold leading-[1.15] tracking-[-0.02em] text-[#242b34]">Top 10 New York apartments for sell now trending</h2>
                        <div class="mt-4 grid gap-1 sm:grid-cols-3">
                            <img src="https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=900&q=80" alt="interior 1" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-32 w-full object-cover sm:h-36">
                            <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80" alt="interior 2" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-32 w-full object-cover sm:h-36">
                            <img src="https://images.unsplash.com/photo-1600607687644-c7171b42498f?auto=format&fit=crop&w=900&q=80" alt="interior 3" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-32 w-full object-cover sm:h-36">
                        </div>
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1800&q=80" alt="villa" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="mt-1 h-[280px] w-full object-cover sm:h-[360px]">

                        <div class="mt-6 space-y-4 text-[15px] leading-8 text-[#445b73]">
                            <p>One neighborhood of New York County is contiguous with The Bronx. Marble Hill at one time was part of Manhattan Island, but the Harlem River Ship Canal, dug in 1895 to improve navigation on the Harlem River, separated it from the remainder of Manhattan.</p>
                            <ol class="list-decimal space-y-2 pl-5">
                                <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                                <li>Aliquam tincidunt mauris eu risus.</li>
                                <li>Vestibulum auctor dapibus neque.</li>
                            </ol>
                        </div>

                        <div class="mt-7 flex items-center gap-3 text-[#6a7d90]">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#f1f5f9]">f</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#f1f5f9]">x</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#f1f5f9]">w</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#f1f5f9]">✉</span>
                        </div>
                    </div>

                    <div class="rounded-[4px] bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-8">
                        <h2 class="text-[30px] font-extrabold leading-[1.1] tracking-[-0.02em] text-[#242b34]">3 thoughts on “About Real Estate”</h2>
                        <div class="mt-6 space-y-6">
                            @foreach ($comments as $comment)
                                <article class="flex gap-4 border-b border-slate-200 pb-6">
                                    <img src="{{ $comment['avatar'] }}" alt="{{ $comment['name'] }}" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-12 w-12 rounded object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[20px] font-semibold leading-none text-[#242b34]">{{ $comment['name'] }}</p>
                                        <p class="mt-2 text-[14px] text-[#6a7d90]">{{ $comment['date'] }}</p>
                                        <p class="mt-3 text-[15px] leading-7 text-[#445b73]">{{ $comment['text'] }}</p>
                                        <button class="mt-3 rounded bg-[#23272f] px-3 py-1 text-[13px] font-semibold text-white">Reply</button>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <h2 class="mt-8 text-[30px] font-extrabold leading-[1.1] tracking-[-0.02em] text-[#242b34]">Leave a Reply</h2>
                        <p class="mt-2 text-[15px] text-[#6a7d90]">Your email address will not be published.</p>
                        <form class="mt-4 space-y-4">
                            <textarea rows="6" placeholder="Comment" class="w-full rounded border border-slate-300 bg-white px-3 py-3 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"></textarea>
                            <div class="grid gap-3 sm:grid-cols-3">
                                <input type="text" placeholder="Name" class="w-full rounded border border-slate-300 px-3 py-2.5 text-[14px] outline-none focus:border-[#1e73be]">
                                <input type="email" placeholder="Email" class="w-full rounded border border-slate-300 px-3 py-2.5 text-[14px] outline-none focus:border-[#1e73be]">
                                <input type="text" placeholder="Website" class="w-full rounded border border-slate-300 px-3 py-2.5 text-[14px] outline-none focus:border-[#1e73be]">
                            </div>
                            <button type="submit" class="rounded bg-[#1279eb] px-8 py-2.5 text-[15px] font-semibold text-white hover:bg-[#0f6fcb]">Post Comment</button>
                        </form>
                    </div>

                    <div>
                        <h2 class="text-[30px] font-extrabold leading-[1.1] tracking-[-0.02em] text-[#242b34]">Similar Posts</h2>
                        <div class="mt-4 grid gap-5 sm:grid-cols-2 lg:grid-cols-2">
                            @foreach ($similarPosts as $similar)
                                <article class="overflow-hidden rounded-[4px] bg-white shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200">
                                    <img src="{{ $similar['image'] }}" alt="{{ $similar['title'] }}" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-48 w-full object-cover">
                                    <div class="p-5">
                                        <h2 class="text-[18px] font-bold leading-[1.25] tracking-[-0.01em] text-[#242b34]">{{ $similar['title'] }}</h2>
                                        <p class="mt-2 text-[14px] text-[#6a7d90]">{{ $similar['date'] }}</p>
                                        <p class="mt-3 text-[15px] leading-7 text-[#445b73]">{{ $similar['excerpt'] }}</p>
                                        <a href="{{ route('blog') }}" class="mt-4 inline-flex items-center gap-2 text-[14px] font-semibold text-[#242b34] hover:text-[#1e73be]">Continue reading <span aria-hidden="true">›</span></a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </article>

                <aside class="space-y-6 self-start lg:sticky lg:top-24">
                    <div class="rounded-[4px] bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-6">
                        <h2 class="text-[18px] font-semibold tracking-[-0.02em] text-[#1f2a44]">Featured Listings</h2>
                        <div class="mt-5 space-y-5">
                            @foreach ($featuredListings as $listing)
                                <article class="flex items-center gap-3">
                                    <img src="{{ $listing['image'] }}" alt="{{ $listing['title'] }}" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-16 w-24 rounded-[4px] object-cover">
                                    <div class="min-w-0">
                                        <p class="text-[14px] font-semibold leading-5 text-[#242b34]">{{ $listing['title'] }}</p>
                                        <p class="mt-1 text-[14px] font-semibold text-[#1e73be]">{{ $listing['price'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[4px] bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-6">
                        <h2 class="text-[18px] font-semibold tracking-[-0.02em] text-[#242b34]">Recent Posts</h2>
                        <ul class="mt-4 space-y-3 text-[15px] text-[#445b73]">
                            <li class="inline-flex items-center gap-2">📄 Search widget on the right</li>
                            <li class="inline-flex items-center gap-2">📄 Buying a Home</li>
                            <li class="inline-flex items-center gap-2">📄 Manhattan Apartments</li>
                            <li class="inline-flex items-center gap-2">📄 Why Live in New York</li>
                            <li class="inline-flex items-center gap-2">📄 Selling Your Home</li>
                        </ul>
                    </div>

                    <div class="rounded-[4px] bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-6">
                        <h2 class="text-[18px] font-semibold tracking-[-0.02em] text-[#242b34]">Search Keyword</h2>
                        <form class="mt-4 space-y-3">
                            <input type="text" placeholder="Type Keyword" class="w-full rounded border border-slate-300 px-3 py-2.5 text-[14px] outline-none focus:border-[#1e73be]">
                            <button type="submit" class="w-full rounded bg-[#1279eb] px-4 py-2.5 text-[15px] font-semibold text-white hover:bg-[#0f6fcb]">Search</button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
