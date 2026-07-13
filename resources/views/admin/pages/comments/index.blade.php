@extends('admin.layouts.app')

@section('title', 'Manage Comments')
@section('page', 'comments')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Blog Comments</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Moderate, approve, and delete comments left by readers on your blog articles.</p>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">Filter comments by approval status.</div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="flex items-center gap-2">
                <label for="comments-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
                <select id="comments-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="all">All</option>
                  <option value="active">Approved</option>
                  <option value="inactive">Pending</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="comments-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Post</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Email</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Comment</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Date</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
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
      const table = $('#comments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route("admin.comments.data") }}',
          data: function (d) {
            d.status_filter = $('#comments-status-filter').val();
          }
        },
        columns: [
          { data: 'post_title', name: 'post.title' },
          { data: 'name', name: 'name' },
          { data: 'email', name: 'email' },
          { data: 'content_excerpt', name: 'content' },
          { 
            data: 'created_at', 
            name: 'created_at',
            render: function(data) {
              return new Date(data).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
              });
            }
          },
          { data: 'status_label', name: 'status' },
          { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        classes: {
          sLengthSelect: 'h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 outline-none focus:border-brand-500',
          sFilterInput: 'h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 outline-none focus:border-brand-500'
        }
      });

      $('#comments-status-filter').on('change', function () {
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
