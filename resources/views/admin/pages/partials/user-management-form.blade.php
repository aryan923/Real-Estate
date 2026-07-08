@php
  $selectedRoleId = (int) old('role_id', $editingUser?->roles->first()?->id ?? 0);
  $initialProfilePicture = $editingUser?->profile_pic ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($editingUser->profile_pic) : '';
@endphp

@if ($errors->any())
  <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-500/10 dark:text-red-300">
    <p class="font-medium">Please fix the highlighted fields and try again.</p>
  </div>
@endif

<form method="POST" action="{{ $editingUser ? route('admin.user-management.update', $editingUser) : route('admin.user-management.store') }}" class="space-y-6" enctype="multipart/form-data">
  @csrf
  @if ($editingUser)
    @method('PUT')
  @endif

  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
      <label for="profile_pic" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Picture {{ $editingUser ? '(optional)' : '' }}</label>
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <img
          id="profile-pic-preview"
          src="{{ $initialProfilePicture }}"
          alt="Profile picture preview"
          data-initial-src="{{ $initialProfilePicture }}"
          class="h-16 w-16 rounded-full border border-gray-200 object-cover dark:border-gray-700 {{ $initialProfilePicture ? '' : 'hidden' }}"
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
      @error('profile_pic')
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
      <input
        id="name"
        name="name"
        type="text"
        value="{{ old('name', $editingUser?->name) }}"
        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white/90"
        placeholder="Enter full name"
      />
      @error('name')
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
      <input
        id="email"
        name="email"
        type="email"
        value="{{ old('email', $editingUser?->email) }}"
        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white/90"
        placeholder="Enter email"
      />
      @error('email')
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="phone" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
      <input
        id="phone"
        name="phone"
        type="text"
        value="{{ old('phone', $editingUser?->phone) }}"
        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white/90"
        placeholder="Enter phone number"
      />
      @error('phone')
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="role_id" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
      <select
        id="role_id"
        name="role_id"
        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white/90"
      >
        <option value="">Select role</option>
        @foreach ($roles as $role)
          <option value="{{ $role->id }}" @selected($selectedRoleId === $role->id)>{{ $role->name }}</option>
        @endforeach
      </select>
      @error('role_id')
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Password {{ $editingUser ? '(optional)' : '' }}</label>
      <input
        id="password"
        name="password"
        type="password"
        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white/90"
        placeholder="{{ $editingUser ? 'Leave blank to keep current password' : 'Enter password' }}"
      />
      @error('password')
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-end pb-1">
      <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
        <input
          type="checkbox"
          name="is_admin"
          value="1"
          class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
          @checked((bool) old('is_admin', $editingUser?->is_admin))
        >
        Admin user
      </label>
      @error('is_admin')
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
    </div>
  </div>

  <div class="flex flex-wrap items-center gap-3">
    <button
      type="submit"
      class="inline-flex items-center rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600"
    >
      {{ $editingUser ? 'Update User' : 'Create User' }}
    </button>

    <a
      href="{{ route('admin.user-management') }}"
      class="inline-flex items-center rounded-lg border border-gray-200 px-5 py-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
    >
      Back to Listing
    </a>
  </div>
</form>

@once
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('profile_pic');
        const preview = document.getElementById('profile-pic-preview');

        if (!input || !preview) {
          return;
        }

        input.addEventListener('change', function () {
          const file = input.files && input.files[0] ? input.files[0] : null;

          if (!file) {
            const fallbackSrc = preview.getAttribute('data-initial-src') || '';

            if (!fallbackSrc) {
              preview.src = '';
              preview.classList.add('hidden');
              return;
            }

            preview.src = fallbackSrc;
            preview.classList.remove('hidden');
            return;
          }

          preview.src = URL.createObjectURL(file);
          preview.classList.remove('hidden');
        });
      });
    </script>
  @endpush
@endonce
