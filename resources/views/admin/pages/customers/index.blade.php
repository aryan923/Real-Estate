@extends('admin.layouts.app')

@section('title', 'Customers')
@section('page', 'customers')

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
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Customers</h1>
            </div>
            <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              <span>Add Customer</span>
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="customers-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Email</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Phone</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Occupation</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Budget Range</th>
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

      const table = window.jQuery('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.customers.data') }}'
        },
        columns: [
          { data: 'name', name: 'name', class: 'px-5 py-4 text-sm font-medium text-gray-900 dark:text-white' },
          { data: 'email', name: 'email', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'phone', name: 'phone', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'occupation', name: 'occupation', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'budget_range', name: 'budget_range', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'action', name: 'action', orderable: false, searchable: false, class: 'px-5 py-4 text-sm' }
        ],
        order: [[0, 'asc']],
        language: {
          search: "",
          searchPlaceholder: "Search customers...",
          lengthMenu: "Show _MENU_ entries",
          processing: "<div class='text-brand-500 font-medium'>Loading...</div>"
        },
        drawCallback: function () {
          window.jQuery('[data-confirm]').off('click').on('click', function (e) {
            if (!confirm(window.jQuery(this).attr('data-confirm'))) {
              e.preventDefault();
            }
          });
        }
      });

      const searchInput = window.jQuery('.dataTables_filter input');
      searchInput.addClass('h-10 rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-700 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200');
    });
  </script>
@endpush
