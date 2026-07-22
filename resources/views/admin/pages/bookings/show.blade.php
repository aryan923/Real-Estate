@extends('admin.layouts.app')

@section('title', 'Booking ' . $booking->booking_no)
@section('page', 'bookings')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-6">
        
        <!-- Header Panel Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Booking #{{ $booking->booking_no }}</h1>
                @switch($booking->status)
                  @case('confirmed')
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">Confirmed</span>
                    @break
                  @case('cancelled')
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-bold uppercase bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400">Cancelled</span>
                    @break
                  @default
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">Pending Approval</span>
                @endswitch
              </div>
              <p class="text-xs text-gray-500 mt-1">Recorded on {{ $booking->booking_date ? $booking->booking_date->format('d M Y') : 'N/A' }}</p>
            </div>
            
            <div class="flex items-center gap-3">
              @if ($booking->status === 'pending_approval')
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                  Edit Details
                </a>
                <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" data-confirm="Cancel this booking and release the unit?">
                  @csrf
                  <button type="submit" class="rounded-lg bg-red-500 hover:bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition">
                    Cancel Booking
                  </button>
                </form>
              @endif
              <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">Back</a>
            </div>
          </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <!-- Column 1: Financial & Purchase Overview -->
          <div class="md:col-span-2 space-y-6">
            
            <!-- Unit & Project details -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Unit Specifications</h2>
              <dl class="grid grid-cols-2 gap-x-4 gap-y-4">
                <div>
                  <dt class="text-xs text-gray-400 uppercase tracking-wider">Project</dt>
                  <dd class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-1">
                    <a href="{{ route('admin.projects.show', $booking->project_id) }}" class="text-brand-600 hover:underline dark:text-brand-400">
                      {{ $booking->project?->name ?: 'N/A' }}
                    </a>
                  </dd>
                </div>
                <div>
                  <dt class="text-xs text-gray-400 uppercase tracking-wider">Unit Number</dt>
                  <dd class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-1">
                    {{ $booking->property?->unit_number ?: 'N/A' }} ({{ $booking->property?->tower ?: 'N/A' }})
                  </dd>
                </div>
                <div>
                  <dt class="text-xs text-gray-400 uppercase tracking-wider">Floor</dt>
                  <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">Floor {{ $booking->property?->floor ?: 'N/A' }}</dd>
                </div>
                <div>
                  <dt class="text-xs text-gray-400 uppercase tracking-wider">BHK / Facing</dt>
                  <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $booking->property?->bedrooms ?: 'N/A' }} BHK / Facing {{ $booking->property?->facing ?: 'N/A' }}</dd>
                </div>
              </dl>
            </div>

            <!-- Financial metrics cards -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Financial Invoice</h2>
              <div class="grid grid-cols-3 gap-6 text-center">
                <div class="border border-gray-100 rounded-xl p-4 dark:border-gray-800">
                  <span class="text-[10px] text-gray-400 uppercase font-semibold">Total Agreement Value</span>
                  <div class="mt-2 text-lg font-extrabold text-gray-900 dark:text-white">₹{{ number_format($booking->total_agreement_value, 2) }}</div>
                </div>
                <div class="border border-gray-100 rounded-xl p-4 dark:border-gray-800 bg-emerald-50/50 dark:bg-emerald-500/5">
                  <span class="text-[10px] text-emerald-600 dark:text-emerald-400 uppercase font-semibold">Booking Amount Paid</span>
                  <div class="mt-2 text-lg font-extrabold text-emerald-600 dark:text-emerald-400">₹{{ number_format($booking->booking_amount, 2) }}</div>
                </div>
                <div class="border border-gray-100 rounded-xl p-4 dark:border-gray-800 bg-amber-50/50 dark:bg-amber-500/5">
                  <span class="text-[10px] text-amber-600 dark:text-amber-400 uppercase font-semibold">Pending Balance Due</span>
                  <div class="mt-2 text-lg font-extrabold text-amber-600 dark:text-amber-400">₹{{ number_format($booking->total_agreement_value - $booking->booking_amount, 2) }}</div>
                </div>
              </div>
            </div>

            <!-- Agreement and Notes -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Agreement Document & Notes</h2>
              <div class="space-y-4">
                @if($booking->agreement_file_path)
                  <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl dark:bg-white/[0.02]">
                    <div class="flex items-center gap-3">
                      <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                      </svg>
                      <div>
                        <span class="text-sm font-semibold text-gray-800 dark:text-white">Signed Purchase Agreement</span>
                        <span class="text-[10px] text-gray-400 block">Uploaded file attachment</span>
                      </div>
                    </div>
                    <a href="{{ asset($booking->agreement_file_path) }}" target="_blank" class="rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 px-4 py-2 text-xs font-semibold dark:bg-brand-500/10 dark:text-brand-400">
                      Download file
                    </a>
                  </div>
                @else
                  <div class="text-center py-4 bg-gray-50 rounded-xl dark:bg-white/[0.02] text-xs text-gray-500">
                    No purchase agreement document uploaded yet.
                  </div>
                @endif

                <div class="mt-4">
                  <span class="text-xs text-gray-400 uppercase font-semibold">Terms & Notes:</span>
                  <p class="text-sm text-gray-700 dark:text-gray-300 mt-2 bg-gray-50 dark:bg-white/[0.02] p-4 rounded-xl leading-relaxed">
                    {!! nl2br(e($booking->notes ?: 'No special notes or customized installment slabs logged.')) !!}
                  </p>
                </div>
              </div>
            </div>

          </div>

          <!-- Column 2: Customer details profile -->
          <div class="space-y-6">
            
            <!-- Customer profile details -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Customer Profile</h2>
              <div class="flex items-center gap-4 mb-4">
                <div class="h-12 w-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-bold dark:bg-brand-500/10 dark:text-brand-400">
                  {{ strtoupper(substr($booking->customer?->name ?? 'CU', 0, 2)) }}
                </div>
                <div>
                  <a href="{{ route('admin.customers.show', $booking->customer_id) }}" class="text-sm font-bold text-gray-900 hover:text-brand-600 dark:text-white dark:hover:text-brand-400 block">
                    {{ $booking->customer?->name ?: 'N/A' }}
                  </a>
                  <span class="text-xs text-gray-500 block">{{ $booking->customer?->occupation ?: 'Occupation N/A' }}</span>
                </div>
              </div>

              <dl class="space-y-3 text-sm">
                <div>
                  <dt class="text-xs text-gray-400">Email Address</dt>
                  <dd class="font-semibold text-gray-700 dark:text-gray-300 mt-0.5">{{ $booking->customer?->email ?: 'N/A' }}</dd>
                </div>
                <div>
                  <dt class="text-xs text-gray-400">Phone Number</dt>
                  <dd class="font-semibold text-gray-700 dark:text-gray-300 mt-0.5">{{ $booking->customer?->phone ?: 'N/A' }}</dd>
                </div>
              </dl>
            </div>

            <!-- Installments Payment status -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                <h2 class="text-md font-bold text-gray-900 dark:text-white">Installment Receipts</h2>
                <a href="{{ route('admin.payments.create', ['booking_id' => $booking->id]) }}" class="inline-flex items-center gap-1 text-[10px] font-semibold bg-brand-50 text-brand-700 px-2 py-1 rounded dark:bg-brand-500/10 dark:text-brand-400">
                  + Record Payment
                </a>
              </div>
              
              @if($booking->payments->isEmpty())
                <div class="text-center py-4">
                  <p class="text-xs text-gray-500 mb-3">No payments/installments recorded yet.</p>
                  <form method="POST" action="{{ route('admin.bookings.generate-installments', $booking) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 text-xs font-semibold">
                      Generate Milestone Slabs
                    </button>
                  </form>
                </div>
              @else
                <ul class="space-y-3">
                  @foreach($booking->payments as $pay)
                    <li class="flex items-center justify-between border-b border-gray-50 dark:border-gray-800/50 pb-2 last:border-0 last:pb-0 text-xs">
                      <div>
                        <span class="font-bold text-gray-700 dark:text-gray-300">
                          ₹{{ number_format($pay->amount_paid > 0 ? $pay->amount_paid : $pay->amount_due, 2) }}
                        </span>
                        <span class="text-gray-400 block">
                          {{ str_replace('_', ' ', ucfirst($pay->installment_stage)) }} - Due: {{ $pay->due_date ? $pay->due_date->format('d M Y') : 'N/A' }}
                        </span>
                      </div>
                      <span class="inline-flex items-center rounded-md px-2 py-0.5 font-bold uppercase text-[9px] {{ $pay->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($pay->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $pay->status }}
                      </span>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>

          </div>

        </div>

      </div>
    </div>
  </main>
@endsection
