@extends('admin.layouts.app')

@section('title', 'Agents')
@section('page', 'agents')

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
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Agents</h1>
            </div>
            <div class="flex flex-wrap items-center gap-4">
              <div>
                <select id="filter-featured" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                  <option value="">All Agents</option>
                  <option value="yes">Featured Only</option>
                  <option value="no">Not Featured Only</option>
                </select>
              </div>
              <a href="{{ route('admin.agents.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Agent</span>
              </a>
            </div>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="agents-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Photo</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Email</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Phone</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Title</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Company</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Featured</th>
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
      const table = $('#agents-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route("admin.agents.data") }}',
          data: function(d) {
            d.is_featured = $('#filter-featured').val();
          }
        },
        columns: [
          { data: 'photo', name: 'profile_pic', orderable: false },
          { data: 'name', name: 'name' },
          { data: 'email', name: 'email' },
          { data: 'phone', name: 'phone' },
          { data: 'title', name: 'agentMeta.title' },
          { data: 'company', name: 'agentMeta.company' },
          { data: 'featured_label', name: 'agentMeta.is_featured' },
          { data: 'status_label', name: 'is_active' },
          { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
      });

      $('#filter-featured').on('change', function() {
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
