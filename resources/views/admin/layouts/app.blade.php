<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', ($adminSiteName ?? 'Admin Dashboard'))</title>
    <link rel="icon" href="{{ $adminFavicon ?? asset('vendor/tailadmin/favicon.ico') }}">
    <link href="{{ asset('vendor/tailadmin/style.css') }}" rel="stylesheet">
    @stack('styles')
  </head>
  <body
    x-data="{ page: '@yield('page', 'dashboard')', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
  >
    <!-- ===== Preloader Start ===== -->
    <div
      x-show="loaded"
      x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
      class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
    >
      <div
        class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"
      ></div>
    </div>
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
      @include('admin.partials.sidebar')

      <!-- ===== Content Area Start ===== -->
      <div
        class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto"
      >
        <!-- Small Device Overlay Start -->
        <div
          @click="sidebarToggle = false"
          :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
          class="fixed w-full h-screen z-9 bg-gray-900/50"
        ></div>
        <!-- Small Device Overlay End -->

        @yield('content')
      </div>
      <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->

    <script defer src="{{ asset('vendor/tailadmin/bundle.js') }}"></script>
    @stack('scripts')
  </body>
</html>
