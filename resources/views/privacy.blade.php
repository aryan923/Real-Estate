@extends('layouts.master')

@section('title', 'Privacy Policy')

@section('content')
    <header class="relative isolate min-h-[300px] bg-[#08192f] z-30" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>

        <div class="absolute inset-0 bg-[#08192f]"></div>

        <div class="section-shell relative z-10 flex min-h-[180px] flex-col items-center justify-center pt-6 text-center">
            <h1 class="text-[36px] font-medium leading-tight text-white sm:text-[48px]">Privacy Policy</h1>
            <p class="mt-2 text-[16px] text-white/95 sm:text-[18px]">Your privacy is important to us. Learn how we protect your information.</p>
        </div>
    </header>

    <section class="bg-[#f3f3f3] py-12 sm:py-16">
        <div class="section-shell">
            <div class="mx-auto max-w-4xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-10">
                <article class="prose prose-slate max-w-none space-y-6 text-[#42556f] leading-relaxed">
                    <p class="text-sm text-slate-500">Last updated: July 14, 2026</p>
                    
                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">1. Information Collection</h2>
                    <p>We collect information that you submit to us directly, such as when submitting property enquiries or contact forms, including name, email address, phone number, and any message content.</p>

                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">2. Use of Information</h2>
                    <p>We use the information we collect to respond to your queries, connect you with real estate agents, improve our website performance, and send you occasional updates or marketing emails (if opted-in).</p>

                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">3. Cookies and Analytics</h2>
                    <p>Our website uses cookies and sessions to store active dashboard credentials, save user filter selections, and collect anonymous traffic statistics to optimize the user experience.</p>

                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">4. Information Sharing</h2>
                    <p>We do not sell or lease your personal information to third parties. We may share details with trusted real estate professionals to assist you with your property search or request.</p>

                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">5. Data Security</h2>
                    <p>We employ standard secure servers and encryption protocols to protect your personal data against unauthorized access, loss, or misuse.</p>
                </article>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
