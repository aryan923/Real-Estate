@extends('admin.layouts.app')

@section('title', 'Features')
@section('page', 'features')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Features</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage your feature list using the same admin layout.</p>
            </div>
            <button class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
              Add Feature
            </button>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <p class="text-sm text-gray-500 dark:text-gray-400">This page uses the shared admin layout and sidebar navigation.</p>
        </section>
      </div>
    </div>
  </main>
@endsection
