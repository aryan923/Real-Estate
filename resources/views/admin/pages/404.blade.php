@extends('admin.layouts.app')

@section('title', 'Page Not Found')
@section('page', 'notFound')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center dark:border-gray-800 dark:bg-white/[0.03]">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">404 - Page Not Found</h1>
        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">The page you are looking for does not exist in admin pages.</p>
        <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-flex rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Back to Dashboard</a>
      </div>
    </div>
  </main>
@endsection
