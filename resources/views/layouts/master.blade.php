<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'RealEstate') }} | @yield('title', 'Home')</title>

        @if(!empty($settings['site_fav']))
            <link rel="icon" href="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($settings['site_fav']) }}">
        @else
            <link rel="icon" href="{{ asset('vendor/tailadmin/favicon.ico') }}">
        @endif

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="bg-[#f3f7fc] text-slate-700">
        @include('partials.layout.sticky-header')
        @yield('content')
        @stack('scripts')
    </body>
</html>
