@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page', 'userManagement')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Edit User</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Update user details and change role assignment from the dropdown.</p>
            </div>

            <a
              href="{{ route('admin.user-management.create') }}"
              class="inline-flex items-center rounded-xl border border-brand-200 bg-white px-4 py-2.5 text-sm font-semibold text-brand-600 transition hover:border-brand-300 hover:bg-brand-50 dark:border-brand-500/30 dark:bg-brand-500/10 dark:text-brand-400"
            >
              Add New User
            </a>
          </div>
        </div>

        @if (session('status'))
          <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('status') }}
          </div>
        @endif

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          @include('admin.pages.partials.user-management-form', ['editingUser' => $editingUser])
        </section>
      </div>
    </div>
  </main>
@endsection
