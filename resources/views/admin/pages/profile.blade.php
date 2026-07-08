@extends('admin.layouts.app')

@section('title', 'Profile')
@section('page', 'profile')

@section('content')
  @php
    $profileImage = $profileUser?->profile_pic
      ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($profileUser->profile_pic)
      : asset('vendor/tailadmin/src/images/user/owner.jpg');
    $roleNames = $profileUser?->roles->pluck('name')->all() ?? [];
  @endphp

  <div x-data="{ pageName: 'Profile' }">
    @include('admin.partials.admin-header')

    <main>
      <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName"></h2>

          <nav>
            <ol class="flex items-center gap-1.5">
              <li>
                <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="{{ route('admin.dashboard') }}">
                  Home
                  <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </a>
              </li>
              <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName"></li>
            </ol>
          </nav>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
          <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Profile</h3>

          <div class="mb-6 rounded-2xl border border-gray-200 p-5 dark:border-gray-800 lg:p-6">
            <h4 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">Personal Information</h4>

            @if (session('profile_status'))
              <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">
                {{ session('profile_status') }}
              </div>
            @endif

            @if (isset($errors) && $errors->profileUpdate->any())
              <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-500/10 dark:text-red-300">
                Please fix the profile form fields and try again.
              </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-5 lg:grid-cols-2 lg:gap-7 2xl:gap-x-10">
              @csrf
              @method('PUT')

              <div class="lg:col-span-2">
                <label for="profile_pic" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Picture</label>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                  <img
                    id="profile-page-preview"
                    src="{{ $profileImage }}"
                    alt="Profile picture preview"
                    data-initial-src="{{ $profileImage }}"
                    class="h-20 w-20 rounded-full border border-gray-200 object-cover dark:border-gray-700"
                  />
                  <input
                    id="profile_pic"
                    name="profile_pic"
                    type="file"
                    accept="image/png,image/jpeg,image/webp"
                    class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-500 file:px-4 file:py-2 file:font-medium file:text-white hover:file:bg-brand-600 dark:text-gray-300"
                  />
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Allowed: JPG, PNG, WEBP up to 2MB.</p>
                @if (isset($errors) && $errors->profileUpdate->has('profile_pic'))
                  <p class="mt-2 text-sm text-red-500">{{ $errors->profileUpdate->first('profile_pic') }}</p>
                @endif
              </div>

              <div>
                <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $profileUser?->name) }}" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                @if (isset($errors) && $errors->profileUpdate->has('name'))
                  <p class="mt-2 text-sm text-red-500">{{ $errors->profileUpdate->first('name') }}</p>
                @endif
              </div>

              <div>
                <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                <input id="email" name="email" type="email" value="{{ old('email', $profileUser?->email) }}" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                @if (isset($errors) && $errors->profileUpdate->has('email'))
                  <p class="mt-2 text-sm text-red-500">{{ $errors->profileUpdate->first('email') }}</p>
                @endif
              </div>

              <div>
                <label for="phone" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $profileUser?->phone) }}" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                @if (isset($errors) && $errors->profileUpdate->has('phone'))
                  <p class="mt-2 text-sm text-red-500">{{ $errors->profileUpdate->first('phone') }}</p>
                @endif
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Roles</label>
                <div class="min-h-11 flex flex-wrap items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/40">
                  @forelse ($roleNames as $roleName)
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700 dark:bg-brand-500/10 dark:text-brand-300">{{ $roleName }}</span>
                  @empty
                    <span class="text-sm text-gray-500 dark:text-gray-400">No role assigned</span>
                  @endforelse
                </div>
              </div>

              <div class="lg:col-span-2 flex flex-wrap items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Save Profile</button>
              </div>
            </form>
          </div>

          <div class="rounded-2xl border border-gray-200 p-5 dark:border-gray-800 lg:p-6">
            <h4 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">Update Password</h4>

            @if (session('password_status'))
              <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">
                {{ session('password_status') }}
              </div>
            @endif

            @if (isset($errors) && $errors->passwordUpdate->any())
              <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-500/10 dark:text-red-300">
                Please fix the password form fields and try again.
              </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="grid grid-cols-1 gap-5 lg:grid-cols-3 lg:gap-7">
              @csrf
              @method('PUT')

              <div>
                <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                <input id="current_password" name="current_password" type="password" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                @if (isset($errors) && $errors->passwordUpdate->has('current_password'))
                  <p class="mt-2 text-sm text-red-500">{{ $errors->passwordUpdate->first('current_password') }}</p>
                @endif
              </div>

              <div>
                <label for="password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input id="password" name="password" type="password" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                @if (isset($errors) && $errors->passwordUpdate->has('password'))
                  <p class="mt-2 text-sm text-red-500">{{ $errors->passwordUpdate->first('password') }}</p>
                @endif
              </div>

              <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
              </div>

              <div class="lg:col-span-3 flex flex-wrap items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Update Password</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const input = document.getElementById('profile_pic');
      const preview = document.getElementById('profile-page-preview');

      if (!input || !preview) {
        return;
      }

      input.addEventListener('change', function () {
        const file = input.files && input.files[0] ? input.files[0] : null;

        if (!file) {
          const initialSrc = preview.getAttribute('data-initial-src') || '';
          preview.src = initialSrc;
          return;
        }

        preview.src = URL.createObjectURL(file);
      });
    });
  </script>
@endpush
