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
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Agents</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage real estate agents, their account credentials, and detailed metadata.</p>
            </div>
            <a href="{{ route('admin.agents.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Add Agent</a>
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
          url: '{{ route("admin.agents.data") }}'
        },
        columns: [
          { data: 'photo', name: 'profile_pic', orderable: false },
          { data: 'name', name: 'name' },
          { data: 'email', name: 'email' },
          { data: 'phone', name: 'phone' },
          { data: 'title', name: 'agentMeta.title' },
          { data: 'company', name: 'agentMeta.company' },
          { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
      });

      $(document).on('submit', 'form[data-confirm]', function (e) {
        if (!confirm($(this).data('confirm'))) {
          e.preventDefault();
        }
      });
    });
  </script>
@endpush
