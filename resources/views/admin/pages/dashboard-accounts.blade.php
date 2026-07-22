@extends('admin.layouts.app')

@section('title', 'Accounts Dashboard')
@section('page', 'dashboard')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      
      <!-- Greeting Header -->
      <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Welcome back, {{ $user->name }}!</h1>
        <p class="text-xs text-gray-500 mt-1">Accounts & Billing Dashboard portal. Monitor collections, receipts, and overdue installments.</p>
      </div>

      <!-- KPIs Cards -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-emerald-50/10 dark:bg-emerald-500/5">
          <span class="text-xs text-emerald-600 dark:text-emerald-400 uppercase font-semibold">Total Revenue Collected</span>
          <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">₹{{ number_format($totalCollected, 2) }}</div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-amber-50/10 dark:bg-amber-500/5">
          <span class="text-xs text-amber-600 dark:text-amber-400 uppercase font-semibold">Outstanding Collections</span>
          <div class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-2">₹{{ number_format($outstandingCollection, 2) }}</div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-red-50/10 dark:bg-red-500/5">
          <span class="text-xs text-red-600 dark:text-red-400 uppercase font-semibold">Overdue Slabs</span>
          <div class="text-2xl font-black text-red-600 dark:text-red-400 mt-2">{{ $overdueCount }} installments</div>
        </div>
      </div>

      <!-- Main Panels -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Panel 1: Pending & Overdue collections list -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Outstanding Installments due</h2>
          @if($pendingInstallments->isEmpty())
            <p class="text-xs text-gray-500 text-center py-8">No pending collections outstanding.</p>
          @else
            <div class="overflow-x-auto">
              <table class="min-w-full table-auto text-xs text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/40 text-gray-500">
                  <tr>
                    <th class="px-4 py-3 font-semibold uppercase">Customer</th>
                    <th class="px-4 py-3 font-semibold uppercase">Booking Ref</th>
                    <th class="px-4 py-3 font-semibold uppercase">Milestone</th>
                    <th class="px-4 py-3 font-semibold uppercase">Amount Due</th>
                    <th class="px-4 py-3 font-semibold uppercase">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                  @foreach($pendingInstallments as $inst)
                    <tr>
                      <td class="px-4 py-3 font-semibold text-gray-800 dark:text-white">
                        {{ $inst->booking?->customer?->name }}
                      </td>
                      <td class="px-4 py-3 text-gray-500">{{ $inst->booking?->booking_no }}</td>
                      <td class="px-4 py-3 text-gray-500">{{ str_replace('_', ' ', ucfirst($inst->installment_stage)) }}</td>
                      <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">₹{{ number_format($inst->amount_due, 2) }}</td>
                      <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 font-bold uppercase text-[9px] {{ $inst->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}">
                          {{ $inst->status }}
                        </span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>

        <!-- Panel 2: Recent Collections logged -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Recent Payments Received</h2>
          @if($recentCollections->isEmpty())
            <p class="text-xs text-gray-500 text-center py-8">No payments logged recently.</p>
          @else
            <div class="overflow-x-auto">
              <table class="min-w-full table-auto text-xs text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/40 text-gray-500">
                  <tr>
                    <th class="px-4 py-3 font-semibold uppercase">Receipt No</th>
                    <th class="px-4 py-3 font-semibold uppercase">Customer</th>
                    <th class="px-4 py-3 font-semibold uppercase">Milestone</th>
                    <th class="px-4 py-3 font-semibold uppercase">Amount Paid</th>
                    <th class="px-4 py-3 font-semibold uppercase">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                  @foreach($recentCollections as $col)
                    <tr>
                      <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">{{ $col->receipt_no ?: 'N/A' }}</td>
                      <td class="px-4 py-3 text-gray-500">{{ $col->booking?->customer?->name }}</td>
                      <td class="px-4 py-3 text-gray-500">{{ str_replace('_', ' ', ucfirst($col->installment_stage)) }}</td>
                      <td class="px-4 py-3 font-bold text-emerald-600 dark:text-emerald-400">₹{{ number_format($col->amount_paid, 2) }}</td>
                      <td class="px-4 py-3">
                        <a href="{{ route('admin.payments.receipt', $col->id) }}" class="text-brand-500 hover:text-brand-600 font-semibold">PDF Receipt</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>

      </div>

    </div>
  </main>
@endsection
