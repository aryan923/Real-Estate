@extends('admin.layouts.app')

@section('title', 'Roles & Permissions')
@section('page', 'rolesPermissions')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="relative p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="pointer-events-none absolute inset-x-0 -top-6 -z-10 mx-auto h-48 max-w-4xl rounded-full bg-gradient-to-r from-sky-200/40 via-brand-200/40 to-emerald-200/40 blur-3xl dark:from-sky-500/10 dark:via-brand-500/10 dark:to-emerald-500/10"></div>
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="col-span-1 overflow-hidden rounded-3xl border border-gray-200 bg-gradient-to-br from-white via-sky-50/50 to-brand-50/30 p-6 shadow-sm dark:border-gray-800 dark:bg-gradient-to-br dark:from-white/[0.04] dark:via-sky-500/5 dark:to-brand-500/5">
          <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <span class="inline-flex items-center rounded-full border border-brand-200/70 bg-white/70 px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-brand-700 dark:border-brand-500/30 dark:bg-brand-500/10 dark:text-brand-300">Access Control</span>
              <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Roles & Permissions</h1>
              <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-300">Manage roles from this listing and open dedicated pages to add or edit role permissions with grouped access controls.</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="rounded-2xl border border-brand-100 bg-white/80 px-4 py-3 text-sm font-medium text-brand-700 shadow-sm dark:border-brand-500/20 dark:bg-brand-500/10 dark:text-brand-300">
                Total roles: {{ $roles->count() }}
              </div>
              <a
                href="{{ route('admin.roles-permissions.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:-translate-y-0.5 hover:bg-brand-600"
              >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                Add Role
              </a>
            </div>
          </div>
        </div>

        @if (session('status'))
          <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('status') }}
          </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-5 flex items-center justify-between gap-3">
            <div>
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Role Listing</h2>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Search, sort, and jump to clean edit screens.</p>
            </div>
          </div>

          <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">Use table search and filters to find roles quickly.</div>
            <div class="flex items-center gap-2">
              <label for="roles-permission-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Permission Filter</label>
              <select id="roles-permission-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                <option value="all">All</option>
                <option value="0">No permissions</option>
                <option value="1-3">1 to 3</option>
                <option value="4+">4 or more</option>
              </select>
            </div>
          </div>

          <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-inner dark:border-gray-800 dark:bg-gray-900/30">
            <table id="roles-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gradient-to-r from-gray-50 to-sky-50/40 dark:border-gray-800 dark:from-gray-900/80 dark:to-sky-500/5">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Role Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Permissions</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Created</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($roles as $role)
                  <tr class="border-b border-gray-100 transition hover:bg-sky-50/40 last:border-b-0 dark:border-gray-800 dark:hover:bg-sky-500/5">
                    <td class="px-5 py-4 text-sm font-semibold text-gray-800 dark:text-white/90">{{ $role->name }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400" data-permissions="{{ $role->permissions_count }}">
                      <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ $role->permissions_count }} Assigned</span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $role->created_at?->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                      <div class="flex flex-wrap items-center gap-2">
                        <a
                          href="{{ route('admin.roles-permissions.edit', $role) }}"
                          class="inline-flex items-center rounded-lg border border-brand-200 px-3 py-2 text-xs font-semibold text-brand-600 transition hover:border-brand-300 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400"
                        >
                          Edit
                        </a>
                        <form method="POST" action="{{ route('admin.roles-permissions.destroy', $role) }}" data-confirm="Delete this role?">
                          @csrf
                          @method('DELETE')
                          <button
                            type="submit"
                            class="inline-flex items-center rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50 dark:border-red-500/30 dark:text-red-400"
                          >
                            Delete
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
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

      const table = window.jQuery('#roles-table').DataTable({
        pageLength: 10,
        lengthMenu: [[5, 10, 20, 50, -1], [5, 10, 20, 50, 'All']],
        order: [[0, 'asc']],
        columnDefs: [{ targets: 3, orderable: false }],
      });

      window.jQuery.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'roles-table') {
          return true;
        }

        const filter = window.jQuery('#roles-permission-filter').val();
        if (!filter || filter === 'all') {
          return true;
        }

        const rowNode = table.row(dataIndex).node();
        const count = Number(rowNode?.querySelector('[data-permissions]')?.getAttribute('data-permissions') || 0);

        if (filter === '0') {
          return count === 0;
        }

        if (filter === '1-3') {
          return count >= 1 && count <= 3;
        }

        if (filter === '4+') {
          return count >= 4;
        }

        return true;
      });

      window.jQuery('#roles-permission-filter').on('change', function () {
        table.draw();
      });
    });
  </script>
@endpush
