@extends('admin.layouts.app')

@section('title', 'Update Lead Status')
@section('page', 'enquiries')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        
        <!-- Header Panel -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex items-center gap-3">
            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="text-sm font-semibold text-brand-500 hover:text-brand-600">&larr; Back to Lead Details</a>
          </div>
          <h1 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Update Status &amp; Follow-up Notes</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400">Update the lifecycle stage and add internal follow-up logs for Lead #{{ $enquiry->id }} ({{ $enquiry->name }}).</p>
        </div>

        <!-- Form Card -->
        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] max-w-3xl">
          <form action="{{ route('admin.enquiries.update', $enquiry) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Status Field -->
            <div>
              <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Lifecycle Status</label>
              <select name="status" id="status" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                <option value="draft" {{ old('status', $enquiry->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="new" {{ old('status', $enquiry->status) === 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ old('status', $enquiry->status) === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="interested" {{ old('status', $enquiry->status) === 'interested' ? 'selected' : '' }}>Interested</option>
                <option value="follow_up" {{ old('status', $enquiry->status) === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                <option value="site_visit" {{ old('status', $enquiry->status) === 'site_visit' ? 'selected' : '' }}>Site Visit</option>
                <option value="negotiation" {{ old('status', $enquiry->status) === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                <option value="won" {{ old('status', $enquiry->status) === 'won' ? 'selected' : '' }}>Won (Closed Deal)</option>
                <option value="lost" {{ old('status', $enquiry->status) === 'lost' ? 'selected' : '' }}>Lost</option>
              </select>
              @error('status')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Assigned Agent Field -->
            <div>
              <label for="agent_id" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Assigned Agent</label>
              <select name="agent_id" id="agent_id" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                <option value="">-- Unassigned --</option>
                @foreach ($agents as $agent)
                  <option value="{{ $agent->id }}" {{ old('agent_id', $enquiry->agent_id) == $agent->id ? 'selected' : '' }}>
                    {{ $agent->name }}
                  </option>
                @endforeach
              </select>
              @error('agent_id')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Notes Field -->
            <div>
              <label for="note" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Internal Follow-up Note</label>
              <textarea name="note" id="note" rows="6" placeholder="Log details of the conversation, next steps, or specific client requirements here..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('note', $enquiry->note) }}</textarea>
              @error('note')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center gap-3 pt-2">
              <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">Save Changes</button>
              <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">Cancel</a>
            </div>
          </form>
        </section>

      </div>
    </div>
  </main>
@endsection
