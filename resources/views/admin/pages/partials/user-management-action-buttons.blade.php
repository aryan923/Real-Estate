<div class="flex flex-wrap items-center gap-2">
  <a
    href="{{ route('admin.user-management.edit', $user) }}"
    class="inline-flex items-center rounded-lg border border-brand-200 px-3 py-2 text-xs font-medium text-brand-600 transition hover:border-brand-300 hover:bg-brand-50 dark:border-brand-500/30 dark:text-brand-400"
  >
    Edit
  </a>

  @if (auth()->id() !== $user->id)
    <form method="POST" action="{{ route('admin.user-management.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
      @csrf
      @method('DELETE')
      <button
        type="submit"
        class="inline-flex items-center rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-600 transition hover:border-red-300 hover:bg-red-50 dark:border-red-500/30 dark:text-red-400"
      >
        Delete
      </button>
    </form>
  @endif
</div>
