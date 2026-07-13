@extends('admin.layouts.app')

@section('title', 'Create Testimonial')
@section('page', 'testimonials')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Create Testimonial</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Add a new client testimonial or review.</p>
            </div>
            <a href="{{ route('admin.testimonials') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Back</a>
          </div>
        </div>

        @if ($errors->any())
          <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-700 dark:border-rose-800 dark:bg-rose-500/10 dark:text-rose-200">
            <ul class="list-disc space-y-1 pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Client Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Designation / Role (e.g. developer, buyer, seller)</label>
                <input type="text" name="designation" value="{{ old('designation') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Testimonial Content</label>
              <textarea name="content" rows="4" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>{{ old('content') }}</textarea>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Rating (1 to 5 stars)</label>
                <select name="rating" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                  @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>{{ str_repeat('★', $i) }} ({{ $i }} stars)</option>
                  @endfor
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Client Image / Photo</label>
                <div class="flex items-center gap-4">
                  <div id="image-preview-container" class="hidden">
                    <img id="image-preview" src="#" class="h-16 w-16 rounded-full object-cover border" alt="Client photo preview">
                  </div>
                  <input type="file" name="image" id="image-pic-input" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
              </div>
            </div>

            <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
              <input type="hidden" name="status" value="0">
              <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
              Active / Visible
            </div>

            <div class="flex items-center gap-3">
              <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Save Testimonial</button>
              <a href="{{ route('admin.testimonials') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Cancel</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    document.getElementById('image-pic-input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('image-preview');
                const container = document.getElementById('image-preview-container');
                previewImg.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
  </script>
@endpush
