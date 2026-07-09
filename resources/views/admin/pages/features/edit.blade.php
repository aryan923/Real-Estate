@extends('admin.layouts.app')

@section('title', 'Edit Feature')
@section('page', 'features')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Feature</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Update feature details and status.</p>
            </div>
            <a href="{{ route('admin.features') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Back</a>
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
          <form action="{{ route('admin.features.update', $feature) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
              <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Name</label>
              <input type="text" name="name" value="{{ old('name', $feature->name) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Parent Feature</label>
                <select name="parent_id" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                  <option value="">None</option>
                  @foreach ($parentFeatures as $parentFeature)
                    <option value="{{ $parentFeature->id }}" {{ old('parent_id', $feature->parent_id) == $parentFeature->id ? 'selected' : '' }}>{{ $parentFeature->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1" {{ old('status', $feature->status) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                Active
              </div>
            </div>

            <div class="flex items-center gap-3">
              <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Update Feature</button>
              <a href="{{ route('admin.features') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Cancel</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection
