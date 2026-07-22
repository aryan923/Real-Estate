<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', ($adminSiteName ?? 'Admin Dashboard'))</title>
    <link rel="icon" href="{{ $adminFavicon ?? asset('vendor/tailadmin/favicon.ico') }}">
    <link href="{{ asset('vendor/tailadmin/style.css') }}" rel="stylesheet">
    @stack('styles')
    <style>
      [x-cloak] { display: none !important; }
      /* DataTables Dark Mode Overrides */
      .dark .dataTables_wrapper {
        color: #cbd5e1 !important;
      }
      .dark .dataTables_wrapper .dataTables_length,
      .dark .dataTables_wrapper .dataTables_filter,
      .dark .dataTables_wrapper .dataTables_info,
      .dark .dataTables_wrapper .dataTables_processing,
      .dark .dataTables_wrapper .dataTables_paginate {
        color: #cbd5e1 !important;
      }
      .dark .dataTables_wrapper .dataTables_length select,
      .dark .dataTables_wrapper .dataTables_filter input {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #f8fafc !important;
        border-radius: 0.5rem !important;
        padding: 0.375rem 0.75rem !important;
        outline: none !important;
      }
      .dark .dataTables_wrapper .dataTables_length select:focus,
      .dark .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #1e73be !important;
      }
      .dark table {
        background-color: transparent !important;
        border-color: #334155 !important;
      }
      .dark table thead tr {
        background-color: #0f172a !important;
      }
      .dark table thead th {
        color: #f8fafc !important;
        border-bottom: 1px solid #334155 !important;
        background-color: #0f172a !important;
      }
      .dark table tbody tr {
        background-color: transparent !important;
        color: #cbd5e1 !important;
      }
      .dark table tbody tr td {
        border-bottom: 1px solid #1e293b !important;
        background-color: transparent !important;
      }
      .dark table tbody tr:hover td {
        background-color: rgba(255, 255, 255, 0.02) !important;
      }
      .dark table.no-footer {
        border-bottom: 1px solid #334155 !important;
      }
      .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #cbd5e1 !important;
      }
      .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current,
      .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #1e73be !important;
        color: #ffffff !important;
        border: 1px solid #1e73be !important;
        border-radius: 0.375rem !important;
      }
      .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
      .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
      .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
        color: #64748b !important;
      }
      .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #334155 !important;
        color: #ffffff !important;
        border: 1px solid #334155 !important;
        border-radius: 0.375rem !important;
      }
      /* Highlight edit buttons in dark mode */
      .dark table tbody tr td a[href*="/edit"] {
        background-color: rgba(30, 115, 190, 0.15) !important;
        color: #38bdf8 !important;
        border-color: rgba(30, 115, 190, 0.4) !important;
      }
      .dark table tbody tr td a[href*="/edit"]:hover {
        background-color: #1e73be !important;
        color: #ffffff !important;
        border-color: #1e73be !important;
      }
      /* High-visibility select dropdown filter options in dark mode */
      .dark select {
        background-color: #1e293b !important;
        border-color: #475569 !important;
        color: #f1f5f9 !important;
      }
      .dark select option {
        background-color: #1e293b !important;
        color: #f1f5f9 !important;
      }
      .dark select:focus {
        border-color: #1e73be !important;
      }
      /* High-visibility form labels and file inputs in dark mode */
      .dark label {
        color: #cbd5e1 !important;
      }
      .dark input[type="file"] {
        color: #94a3b8 !important;
      }
      .dark input[type="file"]::file-selector-button {
        background-color: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #475569 !important;
      }
      /* Input elements high-visibility in dark mode */
      .dark input[type="text"],
      .dark input[type="number"],
      .dark input[type="password"],
      .dark input[type="email"],
      .dark textarea {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
      }
      .dark input[type="checkbox"] {
        background-color: #1e293b !important;
        border-color: #475569 !important;
      }
      /* Style Back & Cancel buttons and text-gray-700 elements in dark mode */
      .dark a.border-gray-200,
      .dark button.border-gray-200,
      .dark a.dark\:border-gray-800,
      .dark button.dark\:border-gray-800,
      .dark a.border,
      .dark button.border {
        background-color: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #475569 !important;
      }
      .dark a.border-gray-200:hover,
      .dark button.border-gray-200:hover,
      .dark a.dark\:border-gray-800:hover,
      .dark button.dark\:border-gray-800:hover,
      .dark a.border:hover,
      .dark button.border:hover {
        background-color: #334155 !important;
        color: #ffffff !important;
        border-color: #64748b !important;
      }
      .dark .text-gray-700,
      .dark .dark\:text-gray-300 {
        color: #cbd5e1 !important;
      }
      /* Clean up table row and container backgrounds in dark mode */
      .dark .bg-white {
        background-color: rgba(30, 41, 59, 0.5) !important; /* bg-slate-800/50 */
      }
      .dark table,
      .dark table tr,
      .dark table tr td,
      .dark table tr th {
        background-color: transparent !important;
      }
      .dark .dataTables_wrapper {
        background-color: transparent !important;
      }
      /* Brand elements high-visibility in dark mode */
      .dark .text-brand-700,
      .dark .dark\:text-brand-300 {
        color: #38bdf8 !important;
      }
      .dark .bg-white\/70,
      .dark .bg-white\/80,
      .dark .dark\:bg-brand-500\/10 {
        background-color: rgba(30, 41, 59, 0.4) !important;
      }
      .dark .border-brand-200\/70,
      .dark .border-brand-100,
      .dark .dark\:border-brand-500\/30 {
        border-color: rgba(56, 189, 248, 0.3) !important;
      }
    </style>
  </head>
  <body
    x-data="{ page: '@yield('page', 'dashboard')', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
  >
    <!-- ===== Preloader Start ===== -->
    <div
      x-show="loaded"
      x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
      class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
    >
      <div
        class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"
      ></div>
    </div>
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
      @include('admin.partials.sidebar')

      <!-- ===== Content Area Start ===== -->
      <div
        class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto"
      >
        <!-- Small Device Overlay Start -->
        <div
          @click="sidebarToggle = false"
          :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
          class="fixed w-full h-screen z-9 bg-gray-900/50"
        ></div>
        <!-- Small Device Overlay End -->

        @yield('content')
      </div>
      <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->

    <div id="confirmation-modal" class="hidden fixed inset-0 z-[9999] flex min-h-screen items-center justify-center overflow-y-auto bg-slate-950/75 p-4 backdrop-blur-sm">
      <div class="w-full max-w-xl overflow-hidden rounded-[28px] border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
        <h2 id="confirmation-modal-title" class="text-xl font-semibold text-slate-900 dark:text-white">Confirm action</h2>
        <p id="confirmation-modal-message" class="mt-3 text-sm text-slate-500 dark:text-slate-300">Are you sure you want to continue?</p>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
          <button id="confirmation-modal-cancel" type="button" class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Cancel</button>
          <button id="confirmation-modal-confirm" type="button" class="inline-flex justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">Continue</button>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('confirmation-modal');
        const modalTitle = document.getElementById('confirmation-modal-title');
        const modalMessage = document.getElementById('confirmation-modal-message');
        const confirmButton = document.getElementById('confirmation-modal-confirm');
        const cancelButton = document.getElementById('confirmation-modal-cancel');
        let activeForm = null;

        function closeModal() {
          if (!modal) return;
          modal.classList.add('hidden');
          activeForm = null;
        }

        function openModal(title, message) {
          if (!modal) return;
          modalTitle.textContent = title;
          modalMessage.textContent = message;
          modal.classList.remove('hidden');
          modal.scrollTop = 0;
        }

        cancelButton?.addEventListener('click', function () {
          closeModal();
        });

        modal?.addEventListener('click', function (event) {
          if (event.target === modal) {
            closeModal();
          }
        });

        confirmButton?.addEventListener('click', function () {
          if (!activeForm) {
            return;
          }

          activeForm.dataset.confirmed = 'true';
          closeModal();
          activeForm.submit();
        });

        document.addEventListener('submit', function (event) {
          const form = event.target.closest('form[data-confirm]');
          if (!form) {
            return;
          }

          if (form.dataset.confirmed === 'true') {
            return;
          }

          event.preventDefault();
          const title = form.dataset.confirmTitle || 'Please confirm';
          const message = form.dataset.confirm || 'Are you sure you want to continue?';
          const confirmText = form.dataset.confirmButtonText || 'Continue';
          const cancelText = form.dataset.confirmCancelText || 'Cancel';

          if (window.Swal) {
            window.Swal.fire({
              title: title,
              text: message,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: confirmText,
              cancelButtonText: cancelText,
              reverseButtons: true,
              customClass: {
                popup: 'rounded-[28px] border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900',
                confirmButton: 'bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg',
                cancelButton: 'border border-slate-200 text-slate-700 bg-white px-4 py-2 rounded-lg hover:bg-slate-50',
              },
            }).then(function (result) {
              if (result.isConfirmed) {
                form.dataset.confirmed = 'true';
                form.submit();
              }
            });
            return;
          }

          if (modal) {
            openModal(title, message);
            confirmButton.textContent = confirmText;
            cancelButton.textContent = cancelText;
            activeForm = form;
            return;
          }

          if (window.confirm(message)) {
            form.dataset.confirmed = 'true';
            form.submit();
          }
        });
      });
    </script>

    <script defer src="{{ asset('vendor/tailadmin/bundle.js') }}"></script>
    @if (session('status') || session('success') || session('error') || session('warning'))
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          const message = @json(session('status') ?? session('success') ?? session('error') ?? session('warning'));
          const icon = @json(session('error') ? 'error' : (session('warning') ? 'warning' : 'success'));
          const title = @json(session('error') ? 'Error' : (session('warning') ? 'Warning' : 'Success'));

          if (message && window.Swal) {
            window.Swal.fire({
              title: title,
              text: message,
              icon: icon,
              confirmButtonText: 'OK',
              customClass: {
                popup: 'rounded-[28px] border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900',
                confirmButton: 'bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg',
              },
            });
          }
        });
      </script>
    @endif
    @stack('scripts')
  </body>
</html>
