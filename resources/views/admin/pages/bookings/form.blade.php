@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Booking' : 'New Booking')
@section('page', 'bookings')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10" x-data="{ 
    projectId: '{{ old('project_id', $booking->project_id ?? $selectedProjectId ?? '') }}',
    allProperties: {{ json_encode($properties->map(fn($p) => [
      'id' => $p->id,
      'project_id' => $p->project_id,
      'unit_number' => $p->unit_number,
      'tower' => $p->tower,
      'price' => $p->price,
      'status' => $p->inventory_status
    ])) }},
    getFilteredProperties() {
      if (!this.projectId) return [];
      return this.allProperties.filter(p => p.project_id == this.projectId);
    }
  }">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $mode === 'edit' ? 'Edit Booking details' : 'New Purchase Booking' }}
              </h1>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $mode === 'edit' ? 'Update the details of the active booking transaction.' : 'Record a new property unit purchase reservation.' }}
              </p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
              Back to List
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form method="POST" enctype="multipart/form-data" action="{{ $mode === 'edit' ? route('admin.bookings.update', $booking) : route('admin.bookings.store') }}">
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
                <label for="customer_id" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Select Customer <span class="text-red-500">*</span></label>
                <select name="customer_id" id="customer_id" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select Customer Profile</option>
                  @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ old('customer_id', $booking['customer_id'] ?? '') == $c->id ? 'selected' : '' }}>
                      {{ $c->name }} ({{ $c->email }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label for="project_id" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Select Project <span class="text-red-500">*</span></label>
                <select name="project_id" id="project_id" required x-model="projectId" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select Project</option>
                  @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                  @endforeach
                </select>
              </div>

              <div>
                <label for="property_id" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Select Unit / Property <span class="text-red-500">*</span></label>
                <select name="property_id" id="property_id" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select Unit</option>
                  <template x-for="p in getFilteredProperties()" :key="p.id">
                    <option :value="p.id" :selected="p.id == '{{ old('property_id', $booking->property_id ?? $selectedPropertyId ?? '') }}'" x-text="p.tower + ' - Unit ' + p.unit_number + ' (' + p.status.toUpperCase() + ') - Price: ₹' + Number(p.price).toLocaleString('en-IN')"></option>
                  </template>
                </select>
              </div>

              <div>
                <label for="booking_date" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Booking Date <span class="text-red-500">*</span></label>
                <input type="date" name="booking_date" id="booking_date" required value="{{ old('booking_date', ($booking && $booking->booking_date) ? $booking->booking_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="booking_amount" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Booking Amount Collected (₹) <span class="text-red-500">*</span></label>
                <input type="number" name="booking_amount" id="booking_amount" required min="0" step="0.01" value="{{ old('booking_amount', $booking['booking_amount'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="total_agreement_value" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Total Agreement Value (₹) <span class="text-red-500">*</span></label>
                <input type="number" name="total_agreement_value" id="total_agreement_value" required min="0" step="0.01" value="{{ old('total_agreement_value', $booking['total_agreement_value'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Booking Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="pending_approval" {{ old('status', $booking['status'] ?? 'pending_approval') === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                  <option value="confirmed" {{ old('status', $booking['status'] ?? '') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                  <option value="cancelled" {{ old('status', $booking['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </div>

              <div>
                <label for="agreement_file" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Upload Signed Agreement Document (PDF, Word, or Image)</label>
                <input type="file" name="agreement_file" id="agreement_file" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />
                @if($booking && $booking->agreement_file_path)
                  <span class="text-xs text-gray-400 mt-2 block">
                    Current file: <a href="{{ asset($booking->agreement_file_path) }}" target="_blank" class="text-brand-500 font-semibold hover:underline">Download / View Document</a>
                  </span>
                @endif
              </div>
            </div>

            <div class="mt-6">
              <label for="notes" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Agreement Terms / Transaction Notes</label>
              <textarea name="notes" id="notes" rows="4" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Type comments regarding pricing discount, special installment slabs, payment notes...">{{ old('notes', $booking['notes'] ?? '') }}</textarea>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
              <a href="{{ route('admin.bookings.index') }}" class="rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                Cancel
              </a>
              <button type="submit" class="rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
                {{ $mode === 'edit' ? 'Save Changes' : 'Confirm Booking' }}
              </button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection
