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
    @stack('scripts')
  </body>
</html>
