@extends('admin.layouts.app')

@section('title', 'Add Role')
@section('page', 'rolesPermissions')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="relative p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="pointer-events-none absolute inset-x-0 -top-8 -z-10 mx-auto h-48 max-w-4xl rounded-full bg-gradient-to-r from-brand-200/40 via-sky-200/40 to-emerald-200/40 blur-3xl dark:from-brand-500/10 dark:via-sky-500/10 dark:to-emerald-500/10"></div>
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="relative overflow-hidden rounded-3xl border border-gray-200 bg-gradient-to-br from-white via-sky-50/60 to-brand-50/30 p-5 shadow-sm sm:p-6 md:p-7 dark:border-gray-800 dark:bg-gradient-to-br dark:from-white/[0.04] dark:via-sky-500/5 dark:to-brand-500/5">
          <div class="pointer-events-none absolute -top-10 -right-10 hidden h-36 w-36 rounded-full bg-brand-200/35 blur-2xl sm:block dark:bg-brand-500/20"></div>
          <div class="pointer-events-none absolute -bottom-10 -left-10 hidden h-32 w-32 rounded-full bg-sky-200/35 blur-2xl sm:block dark:bg-sky-500/20"></div>

          <div class="relative flex flex-col gap-4 sm:gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
              <h1 class="font-extrabold tracking-tight text-gray-900 dark:text-white" style="font-size: clamp(1.5rem, 2.1vw, 2.3rem); line-height: 1.12;">Add Role</h1>
            </div>

            <a
              href="{{ route('admin.roles-permissions') }}"
              class="mt-3 inline-flex w-full items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:-translate-y-0.5 hover:bg-gray-50 sm:w-auto lg:mt-0 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
            >
              Back to Listing
            </a>
          </div>
        </div>

        <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          @include('admin.pages.partials.role-permission-form', ['editingRole' => null])
        </section>
      </div>
    </div>
  </main>
@endsection