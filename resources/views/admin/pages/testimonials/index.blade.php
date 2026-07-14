@extends('admin.layouts.app')

@section('title', 'Testimonials')
@section('page', 'testimonials')

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
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Testimonials</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage customer testimonials and reviews displayed on the home page.</p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Add Testimonial</a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">Use table search and status filters to find testimonials.</div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="flex items-center gap-2">
                <label for="testimonials-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
                <select id="testimonials-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="testimonials-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Image</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Designation</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Rating</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Featured</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
              </thead>
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
    $(document).ready(function () {
      const table = $('#testimonials-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route("admin.testimonials.data") }}',
          data: function (d) {
            d.status_filter = $('#testimonials-status-filter').val();
          }
        },
        columns: [
          {
            data: 'image',
            name: 'image',
            orderable: false,
            render: function (data) {
              if (data) {
                return '<img src="/storage/' + data + '" class="h-10 w-10 rounded-full object-cover" />';
              }
              return '<div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-semibold text-gray-400">N/A</div>';
            }
          },
          { data: 'name', name: 'name' },
          { data: 'designation', name: 'designation' },
          {
            data: 'rating',
            name: 'rating',
            render: function (data) {
              return '<span class="text-yellow-500">★</span> ' + data;
            }
          },
          { data: 'status_label', name: 'status' },
          { data: 'featured_label', name: 'is_featured' },
          { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        classes: {
          sLengthSelect: 'h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 outline-none focus:border-brand-500',
          sFilterInput: 'h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 outline-none focus:border-brand-500'
        }
      });

      $('#testimonials-status-filter').on('change', function () {
        table.draw();
      });

      $(document).on('submit', 'form[data-confirm]', function (e) {
        if (!confirm($(this).data('confirm'))) {
          e.preventDefault();
        }
      });
    });
  </script>
@endpush
