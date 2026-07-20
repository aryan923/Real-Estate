@extends('admin.layouts.app')

@section('title', 'Cities')
@section('page', 'cities')

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
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Cities</h1>
            </div>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-end">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="flex items-center gap-2">
                <label for="cities-featured-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Featured</label>
                <select id="cities-featured-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="cities-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">State</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Country</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Featured</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="5" class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">Loading cities...</td>
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

      const table = window.jQuery('#cities-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.cities.data') }}',
          data: function (d) {
            d.featured_filter = window.jQuery('#cities-featured-filter').val();
          },
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 20, 50, -1], [5, 10, 20, 50, 'All']],
        order: [[0, 'asc']],
        columns: [
          { data: 'name', name: 'name' },
          { data: 'state_name', name: 'state.name' },
          { data: 'country_name', name: 'country.name' },
          { data: 'featured_label', name: 'is_featured' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
      });

      window.jQuery('#cities-featured-filter').on('change', function () {
        table.ajax.reload();
      });
    });
  </script>
@endpush
