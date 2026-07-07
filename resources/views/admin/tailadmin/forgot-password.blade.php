<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Forgot Password | TailAdmin - Tailwind CSS Admin Dashboard Template</title>
  <link rel="icon" href="{{ asset('vendor/tailadmin/favicon.ico') }}"><link href="{{ asset('vendor/tailadmin/style.css') }}" rel="stylesheet"></head>
  <body
    x-data="{ page: 'comingSoon', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
  >
    <div
      x-show="loaded"
      x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
      class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
    >
      <div
        class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"
      ></div>
    </div>

    <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0">
      <div
        class="relative flex flex-col justify-center w-full h-screen dark:bg-gray-900 sm:p-0 lg:flex-row"
      >
        <div class="flex flex-col flex-1 w-full lg:w-1/2">
          <div class="w-full max-w-md pt-10 mx-auto">
            <a
              href="{{ route('admin.login') }}"
              class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
            >
              <svg
                class="stroke-current"
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
              >
                <path
                  d="M12.7083 5L7.5 10.2083L12.7083 15.4167"
                  stroke=""
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
              Back to sign in
            </a>
          </div>
          <div
            class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto"
          >
            <div>
              <div class="mb-5 sm:mb-8">
                <h1
                  class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md"
                >
                  Forgot Password
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Enter your email address and we will send you a one-time password (OTP) to reset your password.
                </p>
              </div>

              <form id="admin-forgot-form" method="POST" action="{{ route('admin.password.email') }}">
                @csrf
                <div id="forgot-error" class="hidden mb-4 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700"></div>
                <div class="space-y-5">
                  <div>
                    <label
                      class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                    >
                      Email<span class="text-error-500">*</span>
                    </label>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      value="{{ old('email') }}"
                      placeholder="info@gmail.com"
                      class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                    />
                  </div>

                  <div>
                    <button
                      type="submit"
                      class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
                    >
                      Send OTP
                    </button>
                  </div>
                </div>
              </form>
              @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                  {{ $errors->first() }}
                </div>
              @endif
              @if (session('status'))
                <div class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700">
                  {{ session('status') }}
                </div>
              @endif

              <div class="mt-5">
                <p class="text-sm font-normal text-center text-gray-700 dark:text-gray-400 sm:text-start">
                  Remember your password?
                  <a href="{{ route('admin.login') }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400">
                    Sign in
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="items-center hidden w-full h-full bg-gray-50 dark:bg-white/5 lg:grid lg:w-1/2">
          <div class="flex items-center justify-center max-w-xs mx-auto">
            <div>
              <img src="{{ asset('vendor/tailadmin/src/images/logo/logo.svg') }}" alt="Logo" class="mx-auto dark:hidden" />
              <img src="{{ asset('vendor/tailadmin/src/images/logo/logo-dark.svg') }}" alt="Logo" class="hidden mx-auto dark:block" />
              <p class="mt-8 text-center text-sm leading-6 text-gray-500 dark:text-gray-400">
                TailAdmin integrated into Laravel. This reset page is wired for navigation and ready to connect to Laravel password reset logic.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if (session('status') || $errors->any())
      <div id="toast-notification" class="fixed right-4 bottom-4 z-50 hidden max-w-sm rounded-2xl border p-4 shadow-xl opacity-0 transition-opacity duration-500 {{ session('status') ? 'border-emerald-200 bg-emerald-50 text-emerald-900' : 'border-red-200 bg-red-50 text-red-900' }}">
        <div class="flex items-start gap-3">
          <div class="mt-1">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-current/10 text-current">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                <path fill-rule="evenodd" clip-rule="evenodd" d="{{ session('status') ? 'M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z' : 'M8.257 3.099c.765-1.36 2.681-1.36 3.446 0l5.518 9.8A1.75 1.75 0 0116.018 15h-8.036a1.75 1.75 0 01-1.702-1.101l-1.99-3.53a1.75 1.75 0 010-1.648l5.518-9.8zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-2.25a.75.75 0 01-.75-.75V6.5a.75.75 0 011.5 0v3.5A.75.75 0 0110 10.75z' }}" clip-rule="evenodd" />
              </svg>
            </span>
          </div>
          <div>
            <p class="font-semibold">{{ session('status') ? 'Success' : 'Error' }}</p>
            <p class="mt-1 text-sm">{{ session('status') ?? $errors->first() }}</p>
          </div>
        </div>
      </div>
      <script>
        window.addEventListener('DOMContentLoaded', function () {
          var toast = document.getElementById('toast-notification');
          if (!toast) return;
          toast.classList.remove('hidden');
          toast.classList.add('opacity-100');
          setTimeout(function () {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
          }, 4500);
        });
      </script>
    @endif
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-3gJwYp6MEt9XX2wR8W7sllBtiQJsGepq9tm0NGnY0UY=" crossorigin="anonymous"></script>
    <script src="{{ asset('vendor/tailadmin/auth-validation.js') }}"></script>
  <script defer src="{{ asset('vendor/tailadmin/bundle.js') }}"></script></body>
</html>