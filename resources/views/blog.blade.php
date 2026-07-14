@extends('layouts.master')

@section('title', 'Blog')

@section('content')
    @php
        $query = trim((string) request('q', ''));
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
            <p class="mt-4 max-w-2xl text-[17px] text-white/90 sm:text-[19px]">Market updates, buying advice, seller strategy, and neighborhood trends from the {{env('APP_NAME')}} team.</p>
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
                                    <img src="{{ \Illuminate\Support\Str::startsWith($post['image'] ?? '', ['http://', 'https://']) ? $post['image'] : \App\Classes\ImageuploadHelper::getUploadedFileUrl($post['image']) }}" alt="{{ $post['title'] }}" class="aspect-[16/9] w-full object-cover">
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

                    <div class="mt-8">
                        {{ $posts->links() }}
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
                                    <img src="{{ \Illuminate\Support\Str::startsWith($post['image'] ?? '', ['http://', 'https://']) ? $post['image'] : \App\Classes\ImageuploadHelper::getUploadedFileUrl($post['image']) }}" alt="{{ $post['title'] }}" class="h-16 w-20 rounded-[4px] object-cover">
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
                                        <img src="{{ \Illuminate\Support\Str::startsWith($post['image'] ?? '', ['http://', 'https://']) ? $post['image'] : \App\Classes\ImageuploadHelper::getUploadedFileUrl($post['image']) }}" alt="{{ $post['title'] }}" class="h-40 w-full object-cover transition duration-300 hover:scale-[1.02]">
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