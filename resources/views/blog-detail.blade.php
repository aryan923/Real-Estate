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

                $featuredListings = \App\Models\Property::with('images')
                    ->where('status', 'active')
                    ->where('featured', true)
                    ->latest()
                    ->take(5)
                    ->get();
                if ($featuredListings->isEmpty()) {
                    $featuredListings = \App\Models\Property::with('images')
                        ->where('status', 'active')
                        ->latest()
                        ->take(5)
                        ->get();
                }

                $comments = $post->comments()->where('status', true)->latest()->get();

                $similarPosts = \App\Models\Post::where('status', true)->where('id', '!=', $post['id'])->latest()->take(3)->get();
                $postImageUrl = \Illuminate\Support\Str::startsWith($post['image'] ?? '', ['http://', 'https://']) ? $post['image'] : \App\Classes\ImageuploadHelper::getUploadedFileUrl($post['image']);
            @endphp

            <div class="mx-auto grid max-w-[1200px] gap-6 md:grid-cols-[minmax(0,1fr)_280px] lg:grid-cols-[minmax(0,1fr)_300px] xl:grid-cols-[minmax(0,1fr)_320px] md:items-start">
                <article class="space-y-6">
                    <div class="overflow-hidden rounded-[4px] bg-white p-3 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-4">
                        <div class="relative overflow-hidden rounded-[2px] bg-[#d9d9d9]">
                            <img src="{{ $postImageUrl }}" alt="{{ $post['title'] }}" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-[300px] w-full object-cover sm:h-[420px]">
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
                        <h1 class="text-[40px] font-extrabold leading-[1.06] tracking-[-0.02em] text-[#242b34]">{{ $post['title'] }}</h1>
                        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-[14px] text-[#4f6277]">
                            <span>Posted by {{ $post['author'] ?? 'admin' }} on {{ $post['date'] }}</span>
                            <span>{{ $post['category'] }}</span>
                            <span>3 Comments</span>
                        </div>

                        <div class="mt-7 space-y-5 text-[15px] leading-8 text-[#445b73]">
                            {!! nl2br(e($post['content'])) !!}
                        </div>

                        <div class="mt-7 flex items-center gap-3 text-[#6a7d90]">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#f1f5f9]">f</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#f1f5f9]">x</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#f1f5f9]">w</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#f1f5f9]">✉</span>
                        </div>
                    </div>

                    <div class="rounded-[4px] bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-8">
                        <h2 class="text-[30px] font-extrabold leading-[1.1] tracking-[-0.02em] text-[#242b34]">{{ $comments->count() }} thoughts on “{{ $post['title'] }}”</h2>
                        <div class="mt-6 space-y-6">
                            @foreach ($comments as $comment)
                                @php
                                    $avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($comment->email))) . '?d=mp';
                                @endphp
                                <article class="flex gap-4 border-b border-slate-200 pb-6">
                                    <img src="{{ $avatar }}" alt="{{ $comment->name }}" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-12 w-12 rounded object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[20px] font-semibold leading-none text-[#242b34]">{{ $comment->name }}</p>
                                        <p class="mt-2 text-[14px] text-[#6a7d90]">{{ $comment->created_at->format('M d, Y') }}</p>
                                        <p class="mt-3 text-[15px] leading-7 text-[#445b73]">{{ $comment->content }}</p>
                                        <button class="mt-3 rounded bg-[#23272f] px-3 py-1 text-[13px] font-semibold text-white">Reply</button>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <h2 class="mt-8 text-[30px] font-extrabold leading-[1.1] tracking-[-0.02em] text-[#242b34]">Leave a Reply</h2>
                        <p class="mt-2 text-[15px] text-[#6a7d90]">Your email address will not be published.</p>

                        @if (session('status'))
                            <div class="mt-4 rounded bg-emerald-50 border border-emerald-200 p-4 text-sm font-semibold text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('blog.comment.store', ['slug' => $post->slug]) }}" method="POST" class="mt-4 space-y-4">
                            @csrf
                            <textarea name="content" rows="6" placeholder="Comment" class="w-full rounded border border-slate-300 bg-white px-3 py-3 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]" required>{{ old('content') }}</textarea>
                            <div class="grid gap-3 sm:grid-cols-3">
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" class="w-full rounded border border-slate-300 px-3 py-2.5 text-[14px] outline-none focus:border-[#1e73be]" required>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full rounded border border-slate-300 px-3 py-2.5 text-[14px] outline-none focus:border-[#1e73be]" required>
                                <input type="text" name="website" value="{{ old('website') }}" placeholder="Website" class="w-full rounded border border-slate-300 px-3 py-2.5 text-[14px] outline-none focus:border-[#1e73be]">
                            </div>
                            <button type="submit" class="rounded bg-[#1279eb] px-8 py-2.5 text-[15px] font-semibold text-white hover:bg-[#0f6fcb]">Post Comment</button>
                        </form>
                    </div>

                    <div>
                        <h2 class="text-[30px] font-extrabold leading-[1.1] tracking-[-0.02em] text-[#242b34]">Similar Posts</h2>
                        <div class="mt-4 grid gap-5 sm:grid-cols-2 lg:grid-cols-2">
                            @foreach ($similarPosts as $similar)
                                @php
                                    $similarImg = \Illuminate\Support\Str::startsWith($similar['image'] ?? '', ['http://', 'https://']) ? $similar['image'] : \App\Classes\ImageuploadHelper::getUploadedFileUrl($similar['image']);
                                @endphp
                                <article class="overflow-hidden rounded-[4px] bg-white shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200">
                                    <a href="{{ route('blog-detail', ['slug' => $similar->slug]) }}" class="block overflow-hidden">
                                        <img src="{{ $similarImg }}" alt="{{ $similar['title'] }}" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-48 w-full object-cover hover:scale-[1.01] transition-transform duration-200">
                                    </a>
                                    <div class="p-5">
                                        <h2 class="text-[18px] font-bold leading-[1.25] tracking-[-0.01em] text-[#242b34]"><a href="{{ route('blog-detail', ['slug' => $similar->slug]) }}" class="hover:text-[#1e73be]">{{ $similar['title'] }}</a></h2>
                                        <p class="mt-2 text-[14px] text-[#6a7d90]">{{ $similar['date'] }}</p>
                                        <p class="mt-3 text-[15px] leading-7 text-[#445b73]">{{ $similar['excerpt'] }}</p>
                                        <a href="{{ route('blog-detail', ['slug' => $similar->slug]) }}" class="mt-4 inline-flex items-center gap-2 text-[14px] font-semibold text-[#242b34] hover:text-[#1e73be]">Continue reading <span aria-hidden="true">›</span></a>
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
                                @php
                                    $primaryImage = $listing->images->firstWhere('is_primary', true) ?? $listing->images->first();
                                    $imageUrl = $primaryImage ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path) : $imageFallback;
                                    $priceText = is_numeric($listing->price) ? format_price($listing->price) : ($listing->price ?: ($listing->price_info ?: 'Contact for price'));
                                @endphp
                                <article class="flex items-center gap-3">
                                    <a href="{{ route('property-detail', ['slug' => $listing->slug ?: $listing->id]) }}" class="block shrink-0">
                                        <img src="{{ $imageUrl }}" alt="{{ $listing->title }}" onerror="this.onerror=null;this.src='{{ $imageFallback }}';" class="h-16 w-24 rounded-[4px] object-cover hover:opacity-90">
                                    </a>
                                    <div class="min-w-0">
                                        <p class="text-[14px] font-semibold leading-5 text-[#242b34]"><a href="{{ route('property-detail', ['slug' => $listing->slug ?: $listing->id]) }}" class="hover:text-[#1e73be]">{{ $listing->title }}</a></p>
                                        <p class="mt-1 text-[14px] font-semibold text-[#1e73be]">{{ $priceText }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[4px] bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 sm:p-6">
                        <h2 class="text-[18px] font-semibold tracking-[-0.02em] text-[#242b34]">Recent Posts</h2>
                        <ul class="mt-4 space-y-3 text-[15px] text-[#445b73]">
                            @foreach ($recentPosts as $rPost)
                                <li>
                                    <a href="{{ route('blog-detail', ['slug' => $rPost->slug]) }}" class="hover:text-[#1e73be]">📄 {{ $rPost->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
