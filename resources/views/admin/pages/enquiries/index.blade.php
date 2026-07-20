@extends('admin.layouts.app')

@section('title', 'Manage Leads')
@section('page', 'enquiries')

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
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Manage Leads</h1>
            </div>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-end">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="flex items-center gap-2">
                <label for="leads-purpose-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Purpose</label>
                <select id="leads-purpose-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="">All</option>
                  <option value="contact">Contact</option>
                  <option value="info">Request Info</option>
                  <option value="tour">Schedule Tour</option>
                  <option value="agent">Agent Contact</option>
                </select>
              </div>
              <div class="flex items-center gap-2">
                <label for="leads-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
                <select id="leads-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="">All</option>
                  <option value="new">New</option>
                  <option value="contacted">Contacted</option>
                  <option value="interested">Interested</option>
                  <option value="follow_up">Follow Up</option>
                  <option value="site_visit">Site Visit</option>
                  <option value="negotiation">Negotiation</option>
                  <option value="won">Won</option>
                  <option value="lost">Lost</option>
                </select>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="enquiries-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">ID</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Email</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Phone</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Purpose</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Property</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Category</th>
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

      const purposeFilter = window.jQuery('#leads-purpose-filter');
      const statusFilter = window.jQuery('#leads-status-filter');

      const table = window.jQuery('#enquiries-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.enquiries.data') }}',
          data: function (d) {
            d.type = purposeFilter.val();
            d.status = statusFilter.val();
          }
        },
        columns: [
          { data: 'id', name: 'id' },
          { data: 'name', name: 'name' },
          { data: 'email', name: 'email' },
          { data: 'phone', name: 'phone' },
          { 
            data: 'type', 
            name: 'type',
            render: function(data) {
              const types = {
                'contact': 'Contact',
                'info': 'Request Info',
                'tour': 'Schedule Tour',
                'agent': 'Agent Contact'
              };
              return types[data] || data;
            }
          },
          { data: 'property', name: 'property', orderable: false, searchable: false },
          { 
            data: 'enq_for', 
            name: 'enq_for',
            render: function(data) {
              if (!data) return '-';
              return data.charAt(0).toUpperCase() + data.slice(1);
            }
          },
          { data: 'status', name: 'status' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        pageLength: 10,
        lengthMenu: [[5, 10, 20, 50, -1], [5, 10, 20, 50, 'All']],
        order: [[0, 'desc']],
      });

      purposeFilter.on('change', function () {
        table.ajax.reload();
      });

      statusFilter.on('change', function () {
        table.ajax.reload();
      });
    });
  </script>
@endpush
