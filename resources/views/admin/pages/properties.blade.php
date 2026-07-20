@extends('admin.layouts.app')

@section('title', 'Properties')
@section('page', 'properties')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Properties</h1>
            </div>
            <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              <span>Add Property</span>
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-end">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="flex items-center gap-2">
                <label for="properties-type-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Category</label>
                <select id="properties-type-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="flex items-center gap-2">
                <label for="properties-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
                <select id="properties-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="active">Active</option>
                  <option value="pending">Pending</option>
                  <option value="sold">Sold</option>
                  <option value="draft">Draft</option>
                  <option value="external_draft">External Draft</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="properties-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Title</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Image</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Category</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Location</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Price</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
              </thead>
              <tbody></tbody>
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

      const categoryFilter = window.jQuery('#properties-type-filter');
      const statusFilter = window.jQuery('#properties-status-filter');

      const table = window.jQuery('#properties-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.properties.data') }}',
          data: function (d) {
            d.category_filter = categoryFilter.val();
            d.status_filter = statusFilter.val();
          }
        },
        columns: [
          { data: 'title', name: 'title' },
          { data: 'image', name: 'image', orderable: false, searchable: false },
          { data: 'category', name: 'category', orderable: false, searchable: false },
          { data: 'location', name: 'location', orderable: false, searchable: false },
          { data: 'price', name: 'price' },
          { data: 'status', name: 'status' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        pageLength: 10,
        lengthMenu: [[5, 10, 20, 50, -1], [5, 10, 20, 50, 'All']],
        order: [[0, 'asc']],
      });

      categoryFilter.on('change', function () {
        table.ajax.reload();
      });

      statusFilter.on('change', function () {
        table.ajax.reload();
      });
    });
  </script>
@endpush
