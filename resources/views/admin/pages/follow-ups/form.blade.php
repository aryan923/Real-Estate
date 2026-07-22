@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Follow-up' : 'Schedule Follow-up')
@section('page', 'follow-ups')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $mode === 'edit' ? 'Edit Follow-up Event' : 'Schedule Follow-up Call' }}
              </h1>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $mode === 'edit' ? 'Update the follow-up details below.' : 'Schedule a new follow-up call with a lead.' }}
              </p>
            </div>
            @php
              $targetEnquiryId = request('enquiry_id') ?? ($followUp->enquiry_id ?? null);
              $backUrl = $targetEnquiryId ? route('admin.enquiries.show', $targetEnquiryId) : route('admin.enquiries.index');
            @endphp
            <a href="{{ $backUrl }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
              Back to Lead
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form method="POST" action="{{ $mode === 'edit' ? route('admin.follow-ups.update', $followUp) : route('admin.follow-ups.store') }}">
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
                    $preselectedId = old('enquiry_id', $followUp->enquiry_id ?? $selectedEnquiryId ?? '');
                  @endphp
                  @foreach($enquiries as $enquiry)
                    <option value="{{ $enquiry->id }}" {{ $enquiry->id == $preselectedId ? 'selected' : '' }}>
                      {{ $enquiry->name }} ({{ $enquiry->phone ?: 'No Phone' }})
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
                  } elseif ($mode === 'edit' && $followUp->scheduled_at) {
                      $scheduledTimeStr = $followUp->scheduled_at->format('Y-m-d\TH:i');
                  }
                @endphp
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" required value="{{ $scheduledTimeStr }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="reminder_before_minutes" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Reminder Before (Minutes) <span class="text-red-500">*</span></label>
                <input type="number" name="reminder_before_minutes" id="reminder_before_minutes" required min="0" value="{{ old('reminder_before_minutes', $followUp['reminder_before_minutes'] ?? 15) }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="pending" {{ old('status', $followUp['status'] ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="completed" {{ old('status', $followUp['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="cancelled" {{ old('status', $followUp['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </div>

              @if($mode === 'edit' || old('status') === 'completed')
                <div>
                  <label for="outcome" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Call Outcome</label>
                  <select name="outcome" id="outcome" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                    <option value="">No Outcome Logged</option>
                    <option value="interested" {{ old('outcome', $followUp['outcome'] ?? '') === 'interested' ? 'selected' : '' }}>Interested</option>
                    <option value="need_more_time" {{ old('outcome', $followUp['outcome'] ?? '') === 'need_more_time' ? 'selected' : '' }}>Need More Time</option>
                    <option value="not_interested" {{ old('outcome', $followUp['outcome'] ?? '') === 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                    <option value="booked_visit" {{ old('outcome', $followUp['outcome'] ?? '') === 'booked_visit' ? 'selected' : '' }}>Booked Site Visit</option>
                    <option value="no_response" {{ old('outcome', $followUp['outcome'] ?? '') === 'no_response' ? 'selected' : '' }}>No Response</option>
                  </select>
                </div>
              @endif
            </div>

            <div class="mt-6">
              <label for="call_notes" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Conversation Notes</label>
              <textarea name="call_notes" id="call_notes" rows="4" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Write call discussion detail notes here...">{{ old('call_notes', $followUp['call_notes'] ?? '') }}</textarea>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
              <a href="{{ $backUrl }}" class="rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                Cancel
              </a>
              <button type="submit" class="rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
                {{ $mode === 'edit' ? 'Save Changes' : 'Schedule Call' }}
              </button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection
