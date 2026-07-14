@extends('admin.layouts.app')

@section('title', 'Edit Currency')
@section('page', 'currencies')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Currency</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Modify currency details and exchange settings.</p>
            </div>
            <a href="{{ route('admin.currencies') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Back</a>
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
          <form action="{{ route('admin.currencies.update', $currency) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Name (e.g. US Dollar)</label>
                <input type="text" name="name" value="{{ old('name', $currency->name) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Code (e.g. USD)</label>
                <input type="text" name="code" value="{{ old('code', $currency->code) }}" placeholder="USD" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Symbol (e.g. $)</label>
                <input type="text" name="symbol" value="{{ old('symbol', $currency->symbol) }}" placeholder="$" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Exchange Rate (relative to base default)</label>
                <input type="number" name="exchange_rate" step="0.000001" value="{{ old('exchange_rate', number_format($currency->exchange_rate, 6, '.', '')) }}" id="exchange-rate-input" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                <p class="mt-1 text-xs text-gray-400">Note: If selected as default base currency, exchange rate is automatically forced to 1.0</p>
              </div>
            </div>

            <div class="space-y-3">
              <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
                <input type="hidden" name="is_default" value="0">
                <input type="checkbox" name="is_default" id="is-default-checkbox" value="1" {{ old('is_default', $currency->is_default) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                Base Currency (Is Default)
              </div>

              <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1" {{ old('status', $currency->status) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                Active / Enabled
              </div>
            </div>

            <div class="flex items-center gap-3">
              <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Update Currency</button>
              <a href="{{ route('admin.currencies') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Cancel</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const checkbox = document.getElementById('is-default-checkbox');
      const rateInput = document.getElementById('exchange-rate-input');

      checkbox.addEventListener('change', function () {
        if (this.checked) {
          rateInput.value = '1.000000';
          rateInput.setAttribute('readonly', 'readonly');
          rateInput.classList.add('bg-gray-100', 'dark:bg-gray-800');
        } else {
          rateInput.removeAttribute('readonly');
          rateInput.classList.remove('bg-gray-100', 'dark:bg-gray-800');
        }
      });

      if (checkbox.checked) {
        rateInput.setAttribute('readonly', 'readonly');
        rateInput.classList.add('bg-gray-100', 'dark:bg-gray-800');
      }
    });
  </script>
@endpush
