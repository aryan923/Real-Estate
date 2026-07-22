@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Payment Record' : 'Record Payment')
@section('page', 'payments')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $mode === 'edit' ? 'Edit Installment payment record' : 'Record Installment payment' }}
              </h1>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $mode === 'edit' ? 'Update the details of the selected payment installment transaction.' : 'Log a manual milestone collection event.' }}
              </p>
            </div>
            <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
              Back to List
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form method="POST" action="{{ $mode === 'edit' ? route('admin.payments.update', $payment) : route('admin.payments.store') }}">
            @csrf
            @if ($mode === 'edit')
              @method('PUT')
            @endif

            @if ($errors->any())
              <div class="rounded-lg border border-red-200 bg-red-50 p-4 mb-6 text-sm text-red-700 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
                <div class="font-semibold mb-2">Please fix the following errors:</div>
                <ul class="list-disc list-inside space-y-1">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div>
                <label for="booking_id" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Select Booking Code <span class="text-red-500">*</span></label>
                <select name="booking_id" id="booking_id" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select Booking</option>
                  @php
                    $preselectedBooking = old('booking_id', $payment->booking_id ?? $selectedBookingId ?? '');
                  @endphp
                  @foreach($bookings as $booking)
                    <option value="{{ $booking->id }}" {{ $booking->id == $preselectedBooking ? 'selected' : '' }}>
                      {{ $booking->booking_no }} - {{ $booking->customer?->name }} (₹{{ number_format($booking->total_agreement_value, 2) }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label for="installment_stage" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Installment Stage <span class="text-red-500">*</span></label>
                <select name="installment_stage" id="installment_stage" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="token" {{ old('installment_stage', $payment['installment_stage'] ?? '') === 'token' ? 'selected' : '' }}>Token Fee</option>
                  <option value="down_payment" {{ old('installment_stage', $payment['installment_stage'] ?? '') === 'down_payment' ? 'selected' : '' }}>Down Payment</option>
                  <option value="slab_1" {{ old('installment_stage', $payment['installment_stage'] ?? '') === 'slab_1' ? 'selected' : '' }}>Slab Milestone 1</option>
                  <option value="slab_2" {{ old('installment_stage', $payment['installment_stage'] ?? '') === 'slab_2' ? 'selected' : '' }}>Slab Milestone 2</option>
                  <option value="possession" {{ old('installment_stage', $payment['installment_stage'] ?? '') === 'possession' ? 'selected' : '' }}>Possession Handover</option>
                </select>
              </div>

              <div>
                <label for="amount_due" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Amount Due (₹) <span class="text-red-500">*</span></label>
                <input type="number" name="amount_due" id="amount_due" required min="0" step="0.01" value="{{ old('amount_due', $payment['amount_due'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="amount_paid" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Amount Paid (₹) <span class="text-red-500">*</span></label>
                <input type="number" name="amount_paid" id="amount_paid" required min="0" step="0.01" value="{{ old('amount_paid', $payment['amount_paid'] ?? 0.00) }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="due_date" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Due Date <span class="text-red-500">*</span></label>
                <input type="date" name="due_date" id="due_date" required value="{{ old('due_date', ($payment && $payment->due_date) ? $payment->due_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="paid_date" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Date of Payment</label>
                <input type="date" name="paid_date" id="paid_date" value="{{ old('paid_date', ($payment && $payment->paid_date) ? $payment->paid_date->format('Y-m-d') : '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="payment_method" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Payment Method</label>
                <input type="text" name="payment_method" id="payment_method" placeholder="e.g. Bank Transfer, UPI, Cheque" value="{{ old('payment_method', $payment['payment_method'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Milestone Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="pending" {{ old('status', $payment['status'] ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="paid" {{ old('status', $payment['status'] ?? '') === 'paid' ? 'selected' : '' }}>Paid</option>
                  <option value="overdue" {{ old('status', $payment['status'] ?? '') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
              </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
              <a href="{{ route('admin.payments.index') }}" class="rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                Cancel
              </a>
              <button type="submit" class="rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
                {{ $mode === 'edit' ? 'Save Changes' : 'Record Transaction' }}
              </button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection
