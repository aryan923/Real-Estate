@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page', 'categories')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Categories</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage categories with parent and status settings.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Add Category</a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">Use table search and filters to find categories by parent and status.</div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="flex items-center gap-2">
                <label for="categories-parent-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Parent</label>
                <select id="categories-parent-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="null">Top-level</option>
                  @foreach ($parentCategories as $parentCategory)
                    <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="flex items-center gap-2">
                <label for="categories-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
                <select id="categories-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="categories-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Parent</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="4" class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">Loading categories...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (!window.jQuery || !window.jQuery.fn.DataTable) {
        return;
      }

      const table = window.jQuery('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.categories.data') }}',
          data: function (d) {
            d.parent_filter = window.jQuery('#categories-parent-filter').val();
            d.status_filter = window.jQuery('#categories-status-filter').val();
          },
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 20, 50, -1], [5, 10, 20, 50, 'All']],
        order: [[0, 'asc']],
        columns: [
          { data: 'name', name: 'name' },
          { data: 'parent', name: 'parent', orderable: false, searchable: false },
          { data: 'status_label', name: 'status' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
      });

      window.jQuery('#categories-parent-filter, #categories-status-filter').on('change', function () {
        table.ajax.reload();
      });
    });
  </script>
@endpush
