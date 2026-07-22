@extends('admin.layouts.app')

@section('title', 'Follow-ups')
@section('page', 'follow-ups')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
  @include('admin.partials.admin-header')

  <main x-data="{ 
    modalOpen: false, 
    followUpId: null,
    outcome: '', 
    callNotes: '', 
    nextScheduledAt: '',
    openLogModal(id) {
      this.followUpId = id;
      this.outcome = '';
      this.callNotes = '';
      this.nextScheduledAt = '';
      this.modalOpen = true;
    },
    submitOutcome() {
      if (!this.outcome) {
        alert('Please select an outcome.');
        return;
      }
      
      fetch(`/admin/follow-ups/${this.followUpId}/log`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          outcome: this.outcome,
          call_notes: this.callNotes,
          next_scheduled_at: this.nextScheduledAt
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          this.modalOpen = false;
          alert(data.message);
          window.location.reload();
        } else {
          alert('Error logging outcome.');
        }
      });
    }
  }">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Follow-up Log</h1>
              <p class="text-xs text-gray-500">Track and schedule scheduled client follow-up calls.</p>
            </div>
            <a href="{{ route('admin.follow-ups.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              <span>Schedule Follow-up</span>
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-end">
            <div class="flex items-center gap-2">
              <label for="follow-ups-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
              <select id="follow-ups-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                <option value="all">All</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="follow-ups-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Lead Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Phone</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Scheduled Time</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Outcome</th>
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

    <!-- Outcome Logging Modal -->
    <div 
      x-show="modalOpen" 
      class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto bg-gray-500/50 p-4" 
      style="display: none;"
    >
      <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Log Call Outcome</h3>
          <button @click="modalOpen = false" class="text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>
        </div>

        <div class="space-y-4">
          <div>
            <label for="modal-outcome" class="block text-sm font-semibold text-gray-800 dark:text-white mb-2">Call Outcome <span class="text-red-500">*</span></label>
            <select id="modal-outcome" x-model="outcome" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              <option value="">Select Outcome</option>
              <option value="interested">Interested</option>
              <option value="need_more_time">Need More Time</option>
              <option value="not_interested">Not Interested</option>
              <option value="booked_visit">Booked Site Visit</option>
              <option value="no_response">No Response</option>
            </select>
          </div>

          <div>
            <label for="modal-notes" class="block text-sm font-semibold text-gray-800 dark:text-white mb-2">Call Notes / Summary</label>
            <textarea id="modal-notes" x-model="callNotes" rows="3" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Type call conversation notes..."></textarea>
          </div>

          <div>
            <label for="modal-next-date" class="block text-sm font-semibold text-gray-800 dark:text-white mb-2">Schedule Next Follow-up (Optional)</label>
            <input type="datetime-local" id="modal-next-date" x-model="nextScheduledAt" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
            <span class="text-[10px] text-gray-400 mt-1 block">Creates a new pending follow-up event automatically.</span>
          </div>

          <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-4 mt-6">
            <button type="button" @click="modalOpen = false" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">Cancel</button>
            <button type="button" @click="submitOutcome()" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Save Call Record</button>
          </div>
        </div>
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

      const statusFilter = window.jQuery('#follow-ups-status-filter');

      const table = window.jQuery('#follow-ups-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.follow-ups.data') }}',
          data: function (d) {
            d.status_filter = statusFilter.val();
          }
        },
        columns: [
          { data: 'enquiry_name', name: 'enquiry.name', class: 'px-5 py-4 text-sm' },
          { data: 'enquiry_phone', name: 'enquiry.phone', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'scheduled_time', name: 'scheduled_at', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'outcome', name: 'outcome', class: 'px-5 py-4 text-sm' },
          { data: 'status', name: 'status', class: 'px-5 py-4 text-sm' },
          { data: 'action', name: 'action', orderable: false, searchable: false, class: 'px-5 py-4 text-sm' }
        ],
        order: [[2, 'asc']],
        language: {
          search: "",
          searchPlaceholder: "Search follow ups...",
          lengthMenu: "Show _MENU_ entries",
          processing: "<div class='text-brand-500 font-medium'>Loading...</div>"
        },
        drawCallback: function () {
          window.jQuery('[data-confirm]').off('click').on('click', function (e) {
            if (!confirm(window.jQuery(this).attr('data-confirm'))) {
              e.preventDefault();
            }
          });
          
          // Inject live global modal click callback handlers
          window.jQuery('[data-log-id]').off('click').on('click', function () {
            const id = window.jQuery(this).data('log-id');
            // Safely invoke Alpine action handler
            const alpineScope = document.querySelector('main').__x.$data;
            alpineScope.openLogModal(id);
          });
        }
      });

      // Bind index helper back into window for easy Datatable injection access
      window.openLogModal = function (id) {
        // Fetch the component scope and trigger
        const alpineScope = document.querySelector('main').__x.$data;
        alpineScope.openLogModal(id);
      };

      statusFilter.on('change', function () {
        table.draw();
      });

      const searchInput = window.jQuery('.dataTables_filter input');
      searchInput.addClass('h-10 rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-700 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200');
    });
  </script>
@endpush
