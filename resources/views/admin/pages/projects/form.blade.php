@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Project' : 'Add Project')
@section('page', 'projects')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-6">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $mode === 'edit' ? 'Edit Project' : 'Add Project' }}
              </h1>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $mode === 'edit' ? 'Update the project details below.' : 'Create a new housing/commercial project.' }}
              </p>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
              Back to List
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form method="POST" action="{{ $mode === 'edit' ? route('admin.projects.update', $project) : route('admin.projects.store') }}">
            @csrf
            @if ($mode === 'edit')
              @method('PUT')
            @endif

            @if ($errors->any())
              <div class="rounded-lg border border-red-200 bg-red-50 p-4 mb-6 text-sm text-red-700 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
                <div class="font-semibold mb-2">Please fix the following errors:</div>
                <ul class="list-disc list-inside space-y-1">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Project Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name', $project['name'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="slug" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Slug (URL friendly name)</label>
                <input type="text" name="slug" id="slug" placeholder="e.g. green-heights (auto-generated if empty)" value="{{ old('slug', $project['slug'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="location" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Location / Address</label>
                <input type="text" name="location" id="location" value="{{ old('location', $project['location'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="total_units" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Total Planned Units <span class="text-red-500">*</span></label>
                <input type="number" name="total_units" id="total_units" required min="0" value="{{ old('total_units', $project['total_units'] ?? 0) }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Project Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="planning" {{ old('status', $project['status'] ?? '') === 'planning' ? 'selected' : '' }}>Planning</option>
                  <option value="under_construction" {{ old('status', $project['status'] ?? '') === 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                  <option value="completed" {{ old('status', $project['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="cancelled" {{ old('status', $project['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </div>
            </div>

            <div class="mt-6">
              <label for="description" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Description</label>
              <textarea name="description" id="description" rows="5" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">{{ old('description', $project['description'] ?? '') }}</textarea>
            </div>

            <div class="mt-6 flex justify-end gap-3">
              <a href="{{ route('admin.projects.index') }}" class="rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                Cancel
              </a>
              <button type="submit" class="rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
                {{ $mode === 'edit' ? 'Save Changes' : 'Create Project' }}
              </button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection
