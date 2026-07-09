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
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Properties</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">View and manage your property listings in one place.</p>
            </div>
            <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Add Property</a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">Use the table below to browse, filter, and edit property listings.</div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="flex items-center gap-2">
                <label for="properties-type-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Category</label>
                <select id="properties-type-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="Residential">Residential</option>
                  <option value="Commercial">Commercial</option>
                  <option value="Industrial">Industrial</option>
                </select>
              </div>
              <div class="flex items-center gap-2">
                <label for="properties-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
                <select id="properties-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="active">Active</option>
                  <option value="pending">Pending</option>
                  <option value="sold">Sold</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="properties-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Title</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Category</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Location</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Price</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">Suburb Home for Sale</td>
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">Industrial</td>
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">Jersey City, NJ</td>
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">$1,000,000</td>
                  <td class="px-5 py-4 text-sm text-green-600 dark:text-green-400">Active</td>
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                    <div class="flex flex-wrap gap-2">
                      <a href="{{ route('admin.properties.edit', ['property' => 1]) }}" class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">Edit</a>
                      <a href="#" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100">Delete</a>
                    </div>
                  </td>
                </tr>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">Luxury Villa Downtown</td>
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">Luxury</td>
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">Manhattan, NY</td>
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">$2,450,000</td>
                  <td class="px-5 py-4 text-sm text-amber-600 dark:text-amber-400">Pending</td>
                  <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                    <div class="flex flex-wrap gap-2">
                      <a href="{{ route('admin.properties.edit', ['property' => 2]) }}" class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">Edit</a>
                      <a href="#" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100">Delete</a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="6" class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">No more records available.</td>
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

      const table = window.jQuery('#properties-table').DataTable({
        pageLength: 10,
        lengthMenu: [[5, 10, 20, 50, -1], [5, 10, 20, 50, 'All']],
        order: [[0, 'asc']],
      });

      const categoryFilter = window.jQuery('#properties-type-filter');
      const statusFilter = window.jQuery('#properties-status-filter');

      window.jQuery.fn.dataTable.ext.search.push(function (settings, data) {
        const categoryValue = categoryFilter.val();
        const statusValue = statusFilter.val();
        const category = data[1] || '';
        const status = data[4] || '';

        if (categoryValue !== 'all' && category !== categoryValue) {
          return false;
        }

        if (statusValue !== 'all' && status.toLowerCase() !== statusValue) {
          return false;
        }

        return true;
      });

      categoryFilter.on('change', function () {
        table.draw();
      });

      statusFilter.on('change', function () {
        table.draw();
      });
    });
  </script>
@endpush
