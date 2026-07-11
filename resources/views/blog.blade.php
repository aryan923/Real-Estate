@extends('layouts.master')

@section('title', 'Blog')

@section('content')
    @php
        $allPosts = $allPosts ?? [
            [
                'title' => 'Manhattan Apartments',
                'slug' => 'manhattan-apartments',
                'excerpt' => 'In Manhattan, uptown means north (more precisely north-northeast, which is the direction the island and its street grid are oriented) and downtown means south.',
                'category' => 'City Living',
                'date' => 'May 27, 2014',
                'read_time' => '5 min read',
                'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Why Live in New York',
                'slug' => 'why-live-in-new-york',
                'excerpt' => 'In contrast with New York City urban atmosphere, the vast majority of the state is dominated by farms, forests, rivers, mountains and lakes.',
                'category' => 'Neighborhood',
                'date' => 'May 27, 2014',
                'read_time' => '4 min read',
                'image' => 'https://images.unsplash.com/photo-1617104551722-3b2d51366400?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'What Luxury Renters Are Prioritizing This Year',
                'slug' => 'what-luxury-renters-are-prioritizing-this-year',
                'excerpt' => 'From wellness-driven layouts to energy-smart systems, learn which features are driving premium lease decisions.',
                'category' => 'Market Trends',
                'date' => 'June 18, 2026',
                'read_time' => '5 min read',
                'image' => 'https://images.unsplash.com/photo-1501183638710-841dd1904471?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Renovation ROI: Which Upgrades Actually Increase Value',
                'slug' => 'renovation-roi-which-upgrades-actually-increase-value',
                'excerpt' => 'Not every improvement adds equal return. Here is where homeowners are seeing the strongest resale gains right now.',
                'category' => 'Home Value',
                'date' => 'June 10, 2026',
                'read_time' => '7 min read',
                'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'How to Evaluate a Neighborhood Like a Local Expert',
                'slug' => 'how-to-evaluate-a-neighborhood-like-a-local-expert',
                'excerpt' => 'A field-tested framework for comparing commute access, long-term growth indicators, and quality-of-life signals.',
                'category' => 'Buying Guide',
                'date' => 'June 1, 2026',
                'read_time' => '6 min read',
                'image' => 'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Rental Demand Snapshot: Where Investors Are Looking',
                'slug' => 'rental-demand-snapshot-where-investors-are-looking',
                'excerpt' => 'A quick look at rental demand shifts and the property types drawing the most interest from experienced investors.',
                'category' => 'Investing',
                'date' => 'May 24, 2026',
                'read_time' => '5 min read',
                'image' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1400&q=80',
            ],
        ];

        $query = trim((string) request('q', ''));

        $posts = $allPosts;

        if ($query !== '') {
            $posts = collect($allPosts)
                ->filter(function (array $post) use ($query) {
                    $needle = strtolower($query);

                    return str_contains(strtolower($post['title']), $needle)
                        || str_contains(strtolower($post['excerpt']), $needle)
                        || str_contains(strtolower($post['category']), $needle);
                })
                ->values()
                ->all();
        }

        $recentPosts = array_slice($allPosts, 0, 4);
        $featuredPosts = array_slice($allPosts, 1, 2);
    @endphp

    <header class="relative isolate min-h-[480px] bg-[#08192f] z-30" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>

        <img src="https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=2200&q=80" alt="Blog hero" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(16,24,36,0.8)_0%,rgba(21,36,53,0.74)_45%,rgba(24,36,48,0.72)_100%)]"></div>

        <div class="section-shell relative z-10 flex min-h-[360px] flex-col items-center justify-center pt-6 text-center sm:min-h-[390px]">
            <p class="text-[12px] font-semibold uppercase tracking-[0.16em] text-sky-200">Insights & Stories</p>
            <h1 class="mt-3 max-w-4xl text-[40px] font-semibold leading-tight text-white sm:text-[56px]">Real Estate Knowledge for Smarter Decisions</h1>
            <p class="mt-4 max-w-2xl text-[17px] text-white/90 sm:text-[19px]">Market updates, buying advice, seller strategy, and neighborhood trends from the RealScape team.</p>
        </div>
    </header>

    <section class="bg-[#f3f7fc] py-16 sm:py-20">
        <div class="section-shell">
            <div class="mx-auto grid w-full max-w-6xl items-start gap-8 xl:grid-cols-12">
                <div class="xl:col-span-8">
                    <div class="grid gap-6 md:grid-cols-2">
                        @forelse ($posts as $post)
                            <article class="rounded-[4px] bg-[#e6eaee] p-3.5 sm:p-4">
                                <div class="overflow-hidden rounded-[4px]">
                                    <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="aspect-[16/9] w-full object-cover">
                                </div>

                                <div class="px-2 pb-2 pt-5 sm:px-3">
                                    <h2 class="text-[20px] font-semibold leading-[1.25] tracking-[-0.02em] text-[#242b34]">
                                        <a href="{{ route('blog-detail', $post['slug']) }}" class="transition hover:text-[#0f5faf]">{{ $post['title'] }}</a>
                                    </h2>
                                    <p class="mt-2 text-[14px] font-medium leading-none text-[#5f7690]">{{ $post['date'] }}</p>
                                    <p class="mt-4 line-clamp-3 text-[14px] leading-[1.7] text-[#4f6277]">{{ $post['excerpt'] }}</p>

                                    <a href="{{ route('blog-detail', $post['slug']) }}" class="mt-6 inline-flex items-center gap-2 text-[14px] font-semibold leading-none text-[#1f252b] transition hover:text-[#0f5faf]">
                                        Continue reading
                                        <span aria-hidden="true">›</span>
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[4px] bg-[#e6eaee] p-7 text-center md:col-span-2 sm:p-10">
                                <h2 class="text-[20px] font-semibold tracking-[-0.02em] text-[#1f2a44]">No blog posts found</h2>
                            </div>
                        @endforelse
                    </div>
                </div>

                <aside class="space-y-6 xl:col-span-4">
                    <div class="rounded-[4px] bg-[#e6eaee] p-4 sm:p-5">
                        <form action="{{ route('blog') }}" method="GET">
                            <label for="blog-search" class="sr-only">Search blog posts</label>
                            <div class="flex gap-2">
                                <input id="blog-search" name="q" type="search" value="{{ $query }}" placeholder="Search posts" class="w-full rounded border border-slate-300 bg-white px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                <button type="submit" class="rounded bg-[#1279eb] px-4 py-2.5 text-[14px] font-semibold text-white hover:bg-[#0f6fcb]">Go</button>
                            </div>
                        </form>
                    </div>

                    <div class="rounded-[4px] bg-[#e6eaee] p-4 sm:p-5">
                        <h3 class="text-[20px] font-semibold tracking-[-0.02em] text-[#242b34]">Recent Blog</h3>
                        <div class="mt-4 space-y-4">
                            @foreach ($recentPosts as $post)
                                <article class="flex items-start gap-3">
                                    <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="h-16 w-20 rounded-[4px] object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="line-clamp-2 text-[14px] font-semibold leading-5 text-[#242b34]"><a href="{{ route('blog-detail', $post['slug']) }}" class="transition hover:text-[#0f5faf]">{{ $post['title'] }}</a></p>
                                        <p class="mt-1 text-[14px] text-[#5f7690]">{{ $post['date'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[4px] bg-[#e6eaee] p-4 sm:p-5">
                        <h3 class="text-[20px] font-semibold tracking-[-0.02em] text-[#242b34]">Featured Blog</h3>
                        <div class="mt-4 space-y-5">
                            @foreach ($featuredPosts as $post)
                                <article>
                                    <a href="{{ route('blog-detail', $post['slug']) }}" class="block overflow-hidden rounded-[4px]">
                                        <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="h-40 w-full object-cover transition duration-300 hover:scale-[1.02]">
                                    </a>
                                    <p class="mt-3 text-[14px] font-semibold leading-6 text-[#242b34]"><a href="{{ route('blog-detail', $post['slug']) }}" class="transition hover:text-[#0f5faf]">{{ $post['title'] }}</a></p>
                                    <p class="mt-1 text-[14px] text-[#5f7690]">{{ $post['date'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection