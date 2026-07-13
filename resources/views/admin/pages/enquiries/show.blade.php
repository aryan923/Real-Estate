@extends('admin.layouts.app')

@section('title', 'Lead Details')
@section('page', 'enquiries')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        
        <!-- Header Panel -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <div class="flex items-center gap-3">
                <a href="{{ route('admin.enquiries.index') }}" class="text-sm font-semibold text-brand-500 hover:text-brand-600">&larr; Back to Leads</a>
              </div>
              <h1 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Lead #{{ $enquiry->id }} - {{ $enquiry->name }}</h1>
            </div>
            <div>
              <a href="{{ route('admin.enquiries.edit', $enquiry) }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">Update Status / Notes</a>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
          <!-- Main Details -->
          <div class="md:col-span-2 space-y-6">
            
            <!-- Lead Contact & Request Information -->
            <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Request Information</h2>
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Name</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $enquiry->name }}</span>
                </div>
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Email</span>
                  <a href="mailto:{{ $enquiry->email }}" class="text-sm font-medium text-brand-500 hover:underline">{{ $enquiry->email }}</a>
                </div>
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Phone</span>
                  @if($enquiry->phone)
                    <a href="tel:{{ $enquiry->phone }}" class="text-sm font-medium text-brand-500 hover:underline">{{ $enquiry->phone }}</a>
                  @else
                    <span class="text-sm text-gray-400 dark:text-gray-500">Not provided</span>
                  @endif
                </div>
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Submitted At</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $enquiry->created_at->format('M d, Y h:i A') }}</span>
                </div>
              </div>

              <!-- Message -->
              <div class="mt-6 border-t border-gray-100 pt-4 dark:border-gray-800">
                <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Customer Message</span>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/40 p-4 rounded-xl whitespace-pre-line">{{ $enquiry->message ?: 'No message text provided.' }}</p>
              </div>
            </section>

            <!-- Tour Details (If type is tour) -->
            @if($enquiry->type === 'tour')
              <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tour Schedule Details</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Requested Date &amp; Time</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                      {{ $enquiry->tour_schedule_time ? \Carbon\Carbon::parse($enquiry->tour_schedule_time)->format('M d, Y h:i A') : 'N/A' }}
                    </span>
                  </div>
                  <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Tour Type</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                      {{ $enquiry->tour_type === 'in_person' ? 'In Person' : ($enquiry->tour_type === 'video_chat' ? 'Video Chat' : 'N/A') }}
                    </span>
                  </div>
                </div>
              </section>
            @endif

            <!-- Associated Property -->
            @if($enquiry->property)
              <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Interested Property</h2>
                <div class="flex items-center gap-4">
                  @php($primaryImage = $enquiry->property->images?->firstWhere('is_primary', true) ?? $enquiry->property->images?->first())
                  @if($primaryImage)
                    <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path) }}" alt="{{ $enquiry->property->title }}" class="h-16 w-16 rounded-lg object-cover">
                  @endif
                  <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $enquiry->property->title }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $enquiry->property->address_line }}, {{ $enquiry->property->locality }}</p>
                    <p class="mt-1 text-sm font-bold text-brand-500">{{ $enquiry->property->price ? '$' . number_format($enquiry->property->price) : 'Contact for price' }}</p>
                  </div>
                </div>
              </section>
            @endif
          </div>

          <!-- Sidebar Status Panel -->
          <div class="space-y-6">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Lead Status</h2>
              
              <!-- Badge -->
              <div class="mb-6">
                <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Current Lifecycle Status</span>
                @php($statusColors = [
                  'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                  'contacted' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                  'interested' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                  'follow_up' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                  'site_visit' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400',
                  'negotiation' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                  'won' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                  'lost' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                ])
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $statusColors[$enquiry->status] ?? 'bg-gray-100 text-gray-800' }}">
                  {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                </span>
              </div>

              <div class="space-y-4 border-t border-gray-100 pt-4 dark:border-gray-800">
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Inquiry Purpose</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                    {{ $enquiry->type === 'contact' ? 'Contact Form' : ($enquiry->type === 'info' ? 'Request Info' : ($enquiry->type === 'tour' ? 'Schedule Tour' : ($enquiry->type === 'agent' ? 'Agent Contact' : $enquiry->type))) }}
                  </span>
                </div>
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Inquiry Category</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                    {{ $enquiry->enq_for ? ucfirst($enquiry->enq_for) : 'N/A' }}
                  </span>
                </div>
              </div>
            </section>

            <!-- Staff Action notes -->
            <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Follow-up Activity</h2>
              <div class="space-y-4">
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Last Action Performed By</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                    {{ $enquiry->actionBy?->name ?? 'System / Pending Follow-up' }}
                  </span>
                </div>
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Last Action Time</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                    {{ $enquiry->performed_at ? \Carbon\Carbon::parse($enquiry->performed_at)->format('M d, Y h:i A') : 'N/A' }}
                  </span>
                </div>
                <div>
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Internal Follow-up Note</span>
                  <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-900/20 p-3 rounded-lg border border-dashed border-gray-200 dark:border-gray-800">
                    {{ $enquiry->note ?: 'No follow-up notes yet.' }}
                  </p>
                </div>
              </div>
            </section>
          </div>
        </div>

      </div>
    </div>
  </main>
@endsection
