@extends('admin.layouts.app')

@section('title', 'Payments')
@section('page', 'payments')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        
        <!-- Metrics Summary Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Receivables</span>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($totalReceivables, 2) }}</span>
            </div>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-emerald-50/20 dark:bg-emerald-500/5">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Total Collection</span>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">₹{{ number_format($totalReceived, 2) }}</span>
            </div>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-amber-50/20 dark:bg-amber-500/5">
            <span class="text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">Pending Outstandings</span>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-bold text-amber-600 dark:text-amber-400">₹{{ number_format($totalPending, 2) }}</span>
            </div>
          </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Installment Ledger</h1>
              <p class="text-xs text-gray-500">Track milestones billing status and download print-ready receipts.</p>
            </div>
            <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              <span>Record Payment</span>
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="payments-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Receipt No</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Booking No</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Stage</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Due Amount</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Paid Amount</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Due Date</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
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

      const table = window.jQuery('#payments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.payments.data') }}'
        },
        columns: [
          { data: 'receipt_number', name: 'receipt_no', class: 'px-5 py-4 text-sm font-semibold text-gray-900 dark:text-white' },
          { data: 'booking_number', name: 'booking.booking_no', class: 'px-5 py-4 text-sm font-semibold text-gray-900 dark:text-white' },
          { data: 'customer_name', name: 'booking.customer.name', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'stage_label', name: 'installment_stage', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'due', name: 'amount_due', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'paid', name: 'amount_paid', class: 'px-5 py-4 text-sm font-bold text-gray-800 dark:text-white' },
          { data: 'due_date_formatted', name: 'due_date', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'status', name: 'status', class: 'px-5 py-4 text-sm' },
          { data: 'action', name: 'action', orderable: false, searchable: false, class: 'px-5 py-4 text-sm' }
        ],
        order: [[0, 'desc']],
        language: {
          search: "",
          searchPlaceholder: "Search payments...",
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
