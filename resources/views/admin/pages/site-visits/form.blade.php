@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Site Visit' : 'Schedule Site Visit')
@section('page', 'site-visits')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $mode === 'edit' ? 'Edit Site Visit Booking' : 'Schedule Site Visit Tour' }}
              </h1>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $mode === 'edit' ? 'Update the site tour arrangements below.' : 'Book a new site tour for a client.' }}
              </p>
            </div>
            <a href="{{ route('admin.site-visits.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
              Back to List
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]" x-data="{ pickupRequired: {{ old('pickup_required', $siteVisit->pickup_required ?? 0) ? 'true' : 'false' }} }">
          <form method="POST" action="{{ $mode === 'edit' ? route('admin.site-visits.update', $siteVisit) : route('admin.site-visits.store') }}">
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
                <label for="enquiry_id" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Select Lead/Enquiry <span class="text-red-500">*</span></label>
                <select name="enquiry_id" id="enquiry_id" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select a Lead</option>
                  @php
                    $preselectedEnq = old('enquiry_id', $siteVisit->enquiry_id ?? $selectedEnquiryId ?? '');
                  @endphp
                  @foreach($enquiries as $enq)
                    <option value="{{ $enq->id }}" {{ $enq->id == $preselectedEnq ? 'selected' : '' }}>
                      {{ $enq->name }} ({{ $enq->phone ?: 'No Phone' }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label for="project_id" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Interested Project <span class="text-red-500">*</span></label>
                <select name="project_id" id="project_id" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select a Project</option>
                  @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ old('project_id', $siteVisit['project_id'] ?? '') == $p->id ? 'selected' : '' }}>
                      {{ $p->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label for="scheduled_at" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Scheduled At <span class="text-red-500">*</span></label>
                @php
                  $scheduledTimeStr = '';
                  if (old('scheduled_at')) {
                      $scheduledTimeStr = old('scheduled_at');
                  } elseif ($mode === 'edit' && $siteVisit->scheduled_at) {
                      $scheduledTimeStr = $siteVisit->scheduled_at->format('Y-m-d\TH:i');
                  }
                @endphp
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" required value="{{ $scheduledTimeStr }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="assigned_executive_id" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Assigned Executive <span class="text-red-500">*</span></label>
                <select name="assigned_executive_id" id="assigned_executive_id" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select an Executive</option>
                  @foreach($executives as $ex)
                    <option value="{{ $ex->id }}" {{ old('assigned_executive_id', $siteVisit['assigned_executive_id'] ?? '') == $ex->id ? 'selected' : '' }}>
                      {{ $ex->name }} ({{ $ex->email }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="scheduled" {{ old('status', $siteVisit['status'] ?? 'scheduled') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                  <option value="visited" {{ old('status', $siteVisit['status'] ?? '') === 'visited' ? 'selected' : '' }}>Visited</option>
                  <option value="not_visited" {{ old('status', $siteVisit['status'] ?? '') === 'not_visited' ? 'selected' : '' }}>Not Visited</option>
                  <option value="rescheduled" {{ old('status', $siteVisit['status'] ?? '') === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                  <option value="cancelled" {{ old('status', $siteVisit['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </div>

              <div>
                <label class="mb-3 block text-sm font-semibold text-gray-800 dark:text-white">Cab Pickup Required?</label>
                <div class="flex items-center gap-3">
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="pickup_required" value="0" />
                    <input type="checkbox" name="pickup_required" value="1" x-model="pickupRequired" class="sr-only peer" />
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-500/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-brand-500"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Yes, arrange cab pickup</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Cab Allocation Panel (Dynamic) -->
            <div x-show="pickupRequired" class="mt-8 border-t border-gray-100 dark:border-gray-800 pt-6 space-y-6" style="display: none;">
              <h3 class="text-sm font-bold text-gray-900 dark:text-white">Cab / Vehicle Logistics</h3>
              
              <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div>
                  <label for="pickup_address" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Pickup Location Address <span class="text-red-500">*</span></label>
                  <input type="text" name="pickup_address" id="pickup_address" :required="pickupRequired" value="{{ old('pickup_address', $siteVisit['pickup_address'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
                </div>
                <div>
                  <label for="driver_name" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Driver Name</label>
                  <input type="text" name="driver_name" id="driver_name" value="{{ old('driver_name', $siteVisit['driver_name'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
                </div>
                <div>
                  <label for="vehicle_number" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Vehicle Number plate</label>
                  <input type="text" name="vehicle_number" id="vehicle_number" placeholder="e.g. DL 3C AY 1234" value="{{ old('vehicle_number', $siteVisit['vehicle_number'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
                </div>
              </div>
            </div>

            <div class="mt-6">
              <label for="feedback" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Visit Feedback / Comments</label>
              <textarea name="feedback" id="feedback" rows="4" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Log comments on the client response to the layout / presentation...">{{ old('feedback', $siteVisit['feedback'] ?? '') }}</textarea>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
              <a href="{{ route('admin.site-visits.index') }}" class="rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                Cancel
              </a>
              <button type="submit" class="rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
                {{ $mode === 'edit' ? 'Save Changes' : 'Schedule Visit' }}
              </button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection
