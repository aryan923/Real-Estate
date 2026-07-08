@php
  $selectedPermissionIds = collect(old('permissions', $editingRole?->permissions->pluck('id')->all() ?? []))
    ->map(fn ($permissionId) => (int) $permissionId)
    ->all();
@endphp

@if ($errors->any())
  <div class="mb-6 rounded-2xl border border-red-200 bg-gradient-to-r from-red-50 to-rose-50 px-5 py-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-500/10 dark:text-red-300">
    <p class="font-semibold">Please fix the highlighted fields and try again.</p>
  </div>
@endif

<form method="POST" action="{{ $editingRole ? route('admin.roles-permissions.update', $editingRole) : route('admin.roles-permissions.store') }}" class="space-y-6">
  @csrf
  @if ($editingRole)
    @method('PUT')
  @endif

  <div>
    <label for="role-name" class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Role Name</label>
    <input
      id="role-name"
      name="name"
      type="text"
      value="{{ old('name', $editingRole?->name) }}"
      class="dark:bg-dark-900 h-12 w-full rounded-xl border border-gray-300 bg-white px-4 text-sm font-medium text-gray-800 placeholder:text-gray-400 shadow-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900/40 dark:text-white/90"
      placeholder="Enter role name"
    />
    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Use a clear name like "Manager" or "Content Editor".</p>
    @error('name')
      <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <div class="mb-3 flex items-center justify-between gap-3">
      <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Permissions By Group</h3>
      <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500 dark:bg-gray-800 dark:text-gray-300">{{ $permissionGroups->flatten()->count() }} permissions</span>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
      @forelse ($permissionGroups as $groupName => $permissions)
        <div class="h-full rounded-2xl border border-gray-200 bg-gradient-to-br from-white to-sky-50/30 p-4 shadow-sm dark:border-gray-800 dark:bg-gradient-to-br dark:from-gray-900/30 dark:to-sky-500/5">
          <div class="mb-3 flex items-center justify-between gap-2">
            <h4 class="text-sm font-bold tracking-wide text-gray-800 dark:text-white/90">{{ $groupName }}</h4>
            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700">
              {{ $permissions->count() }} items
            </span>
          </div>

          <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($permissions as $permission)
              <label class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-3 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-200 hover:bg-brand-50/40 dark:border-gray-800 dark:bg-gray-900/60 dark:text-gray-300 dark:hover:border-brand-500/30 dark:hover:bg-brand-500/5">
                <input
                  type="checkbox"
                  name="permissions[]"
                  value="{{ $permission->id }}"
                  class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                  @checked(in_array($permission->id, $selectedPermissionIds, true))
                >
                <span>{{ ucwords($permission->name) }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @empty
        <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900/30 dark:text-gray-400">
          No permissions available yet. Run the seeder to populate grouped permissions.
        </div>
      @endforelse
    </div>
    @error('permissions')
      <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
    @enderror
    @error('permissions.*')
      <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
    @enderror
  </div>

  <div class="flex flex-wrap items-center gap-3 pt-2">
    <button
      type="submit"
      class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:-translate-y-0.5 hover:bg-brand-600"
    >
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M5 12L10 17L19 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      {{ $editingRole ? 'Update Role' : 'Create Role' }}
    </button>
    <a
      href="{{ route('admin.roles-permissions') }}"
      class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800"
    >
      Back to Listing
    </a>
  </div>
</form>