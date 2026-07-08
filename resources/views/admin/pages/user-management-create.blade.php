@extends('admin.layouts.app')

@section('title', 'Add User')
@section('page', 'userManagement')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Add User</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Create a user and assign a role from the dropdown.</p>
            </div>

            <a
              href="{{ route('admin.user-management') }}"
              class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
            >
              Back to Listing
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          @include('admin.pages.partials.user-management-form', ['editingUser' => null])
        </section>
      </div>
    </div>
  </main>
@endsection
