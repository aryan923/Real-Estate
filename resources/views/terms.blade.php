@extends('layouts.master')

@section('title', 'Terms & Conditions')

@section('content')
    <header class="relative isolate min-h-[300px] bg-[#08192f] z-30" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>

        <div class="absolute inset-0 bg-[#08192f]"></div>

        <div class="section-shell relative z-10 flex min-h-[180px] flex-col items-center justify-center pt-6 text-center">
            <h1 class="text-[36px] font-medium leading-tight text-white sm:text-[48px]">Terms & Conditions</h1>
            <p class="mt-2 text-[16px] text-white/95 sm:text-[18px]">Please read these terms carefully before using our services.</p>
        </div>
    </header>

    <section class="bg-[#f3f3f3] py-12 sm:py-16">
        <div class="section-shell">
            <div class="mx-auto max-w-4xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-10">
                <article class="prose prose-slate max-w-none space-y-6 text-[#42556f] leading-relaxed">
                    <p class="text-sm text-slate-500">Last updated: July 14, 2026</p>
                    
                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">1. Agreement to Terms</h2>
                    <p>By accessing or using our website, you agree to be bound by these Terms & Conditions. If you do not agree, please do not access or use this site.</p>

                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">2. Intellectual Property</h2>
                    <p>All content, features, and functionality on this website, including text, graphics, logos, and images, are the exclusive property of Prop Desk and are protected by international copyright and trademark laws.</p>

                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">3. User Conduct</h2>
                    <p>You agree not to use the website for any unlawful purpose or in any way that interrupts, damages, or impairs the service. You may not attempt to gain unauthorized access to any part of the site.</p>

                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">4. Limitation of Liability</h2>
                    <p>Prop Desk and its associates will not be liable for any direct, indirect, incidental, or consequential damages resulting from the use of, or inability to use, our services or website content.</p>

                    <h2 class="text-2xl font-bold text-[#1f2a44] mt-6">5. Changes to Terms</h2>
                    <p>We reserve the right to modify these terms at any time. Changes will be posted here and will take effect immediately upon publishing.</p>
                </article>
            </div>
        </div>
    </section>

    @include('partials.layout.footer')
@endsection
