@extends('admin.layouts.app')

@section('title', 'Features')
@section('page', 'features')

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
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Features</h1>
            </div>
            <a href="{{ route('admin.features.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              <span>Add Feature</span>
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-end">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="flex items-center gap-2">
                <label for="features-parent-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Parent</label>
                <select id="features-parent-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="null">Top-level</option>
                  @foreach ($parentFeatures as $parentFeature)
                    <option value="{{ $parentFeature->id }}">{{ $parentFeature->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="flex items-center gap-2">
                <label for="features-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
                <select id="features-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="features-table" class="min-w-full table-auto">
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
                  <td colspan="4" class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">Loading features...</td>
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

      const table = window.jQuery('#features-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.features.data') }}',
          data: function (d) {
            d.parent_filter = window.jQuery('#features-parent-filter').val();
            d.status_filter = window.jQuery('#features-status-filter').val();
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

      window.jQuery('#features-parent-filter, #features-status-filter').on('change', function () {
        table.ajax.reload();
      });
    });
  </script>
@endpush
