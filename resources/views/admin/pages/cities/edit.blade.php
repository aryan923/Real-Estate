@extends('admin.layouts.app')

@section('title', 'Edit City')
@section('page', 'cities')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Edit City: {{ $city->name }}</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Update city parameters and featured status.</p>
            </div>
            <a href="{{ route('admin.cities') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Back</a>
          </div>
        </div>

        @if (session('status'))
          <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-700 dark:border-rose-800 dark:bg-rose-500/10 dark:text-rose-200">
            <ul class="list-disc space-y-1 pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form action="{{ route('admin.cities.update', $city) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
              <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300 font-medium">City Name</label>
              <input type="text" value="{{ $city->name }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-500 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300 font-medium">State</label>
              <input type="text" value="{{ $city->state?->name ?? 'N/A' }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-500 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300 font-medium">Country</label>
              <input type="text" value="{{ $city->country?->name ?? 'N/A' }}" class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-500 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled>
            </div>

            <div>
              <label class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300 cursor-pointer">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $city->is_featured) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                Featured City (Show in 'Properties by Area' on Homepage)
              </label>
            </div>

            <div class="flex items-center gap-3">
              <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Update City</button>
              <a href="{{ route('admin.cities') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Cancel</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection
