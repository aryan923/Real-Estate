@extends('admin.layouts.app')

@section('title', 'Site Visits')
@section('page', 'site-visits')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
  @include('admin.partials.admin-header')

  <main x-data="{ 
    modalOpen: false, 
    siteVisitId: null,
    visitStatus: 'visited', 
    feedback: '', 
    openFeedbackModal(id) {
      this.siteVisitId = id;
      this.visitStatus = 'visited';
      this.feedback = '';
      this.modalOpen = true;
    },
    submitFeedback() {
      if (!this.feedback) {
        alert('Please fill out the feedback/notes.');
        return;
      }
      
      fetch(`/admin/site-visits/${this.siteVisitId}/log`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          status: this.visitStatus,
          feedback: this.feedback
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          this.modalOpen = false;
          alert(data.message);
          window.location.reload();
        } else {
          alert('Error logging site visit feedback.');
        }
      });
    }
  }">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Site Visits Directory</h1>
              <p class="text-xs text-gray-500">Coordinate and log client property visits.</p>
            </div>
            <a href="{{ route('admin.site-visits.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              <span>Schedule Site Visit</span>
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-end">
            <div class="flex items-center gap-2">
              <label for="visits-status-filter" class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</label>
              <select id="visits-status-filter" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                <option value="all">All</option>
                <option value="scheduled">Scheduled</option>
                <option value="visited">Visited</option>
                <option value="not_visited">Not Visited</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
          </div>

          <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
            <table id="site-visits-table" class="min-w-full table-auto">
              <thead>
                <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Lead Name</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Project</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Executive</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Scheduled At</th>
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

    <!-- Feedback Logging Modal -->
    <div 
      x-show="modalOpen" 
      class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto bg-gray-500/50 p-4" 
      style="display: none;"
    >
      <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Log Site Visit Feedback</h3>
          <button @click="modalOpen = false" class="text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>
        </div>

        <div class="space-y-4">
          <div>
            <label for="modal-status" class="block text-sm font-semibold text-gray-800 dark:text-white mb-2">Visit Result Status <span class="text-red-500">*</span></label>
            <select id="modal-status" x-model="visitStatus" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              <option value="visited">Visited (Completed)</option>
              <option value="not_visited">Not Visited (No-Show)</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <div>
            <label for="modal-feedback" class="block text-sm font-semibold text-gray-800 dark:text-white mb-2">Customer Feedback / Visit Notes <span class="text-red-500">*</span></label>
            <textarea id="modal-feedback" x-model="feedback" required rows="4" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Describe visitor's remarks, pricing impressions, preferred property unit floor, etc..."></textarea>
          </div>

          <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-4 mt-6">
            <button type="button" @click="modalOpen = false" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">Cancel</button>
            <button type="button" @click="submitFeedback()" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Save Record</button>
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

      const statusFilter = window.jQuery('#visits-status-filter');

      const table = window.jQuery('#site-visits-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.site-visits.data') }}',
          data: function (d) {
            d.status_filter = statusFilter.val();
          }
        },
        columns: [
          { data: 'enquiry_name', name: 'enquiry.name', class: 'px-5 py-4 text-sm' },
          { data: 'project_name', name: 'project.name', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'executive_name', name: 'assignedExecutive.name', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'scheduled_time', name: 'scheduled_at', class: 'px-5 py-4 text-sm text-gray-600 dark:text-gray-400' },
          { data: 'status', name: 'status', class: 'px-5 py-4 text-sm' },
          { data: 'action', name: 'action', orderable: false, searchable: false, class: 'px-5 py-4 text-sm' }
        ],
        order: [[3, 'asc']],
        language: {
          search: "",
          searchPlaceholder: "Search site visits...",
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

      // Bind index helper back into window for easy Datatable button access
      window.openFeedbackModal = function (id) {
        const alpineScope = document.querySelector('main').__x.$data;
        alpineScope.openFeedbackModal(id);
      };

      statusFilter.on('change', function () {
        table.draw();
      });

      const searchInput = window.jQuery('.dataTables_filter input');
      searchInput.addClass('h-10 rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-700 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200');
    });
  </script>
@endpush
