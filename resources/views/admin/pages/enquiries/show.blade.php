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
            <!-- Associated Property with Details Tabs -->
            @if($enquiry->property)
              @php($prop = $enquiry->property)
              <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4 dark:border-gray-800 mb-5">
                  <div class="flex items-center gap-4">
                    @php($primaryImage = $prop->images?->firstWhere('is_primary', true) ?? $prop->images?->first())
                    @if($primaryImage)
                      <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path) }}" alt="{{ $prop->title }}" class="h-16 w-16 rounded-lg object-cover">
                    @else
                      <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                      </div>
                    @endif
                    <div>
                      <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $prop->title }}</h3>
                      <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ implode(', ', array_filter([$prop->address_line, $prop->locality, $prop->city?->name, $prop->state?->name, $prop->country?->name])) }}
                      </p>
                      <p class="mt-1 text-sm font-bold text-brand-500">
                        {{ $prop->price ? '₹ ' . number_format($prop->price) : 'Contact for price' }} {{ $prop->price_type }}
                      </p>
                    </div>
                  </div>
                  <a href="{{ route('admin.properties.edit', $prop) }}" target="_blank" class="text-xs font-semibold text-brand-500 hover:text-brand-600 border border-brand-500/20 hover:border-brand-500 rounded-lg px-3 py-1.5 transition">
                    Edit Property &rarr;
                  </a>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex flex-wrap border-b border-gray-100 dark:border-gray-800 gap-2 mb-4">
                  <button type="button" onclick="switchPropertyTab(event, 'tab-overview')" class="prop-tab-btn px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 border-brand-500 text-brand-500 focus:outline-none transition">Overview</button>
                  <button type="button" onclick="switchPropertyTab(event, 'tab-specs')" class="prop-tab-btn px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none transition">Specifications</button>
                  <button type="button" onclick="switchPropertyTab(event, 'tab-features')" class="prop-tab-btn px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none transition">Amenities ({{ $prop->features->count() }})</button>
                  <button type="button" onclick="switchPropertyTab(event, 'tab-description')" class="prop-tab-btn px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none transition">Description</button>
                </div>

                <!-- Tabs Content -->
                <div class="mt-4">
                  <!-- Overview Tab -->
                  <div id="tab-overview" class="prop-tab-content space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                      <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Purpose</span>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 capitalize">{{ $prop->property_purpose }}</span>
                      </div>
                      <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Categories</span>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                          {{ $prop->categories->pluck('name')->join(', ') ?: '—' }}
                        </span>
                      </div>
                      <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Listing Status</span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 uppercase">
                          {{ $prop->status }}
                        </span>
                      </div>
                      @if($prop->price_info)
                        <div>
                          <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Price Info</span>
                          <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->price_info }}</span>
                        </div>
                      @endif
                      @if($prop->builder_developer)
                        <div>
                          <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Builder / Developer</span>
                          <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->builder_developer }}</span>
                        </div>
                      @endif
                      @if($prop->RERA_no)
                        <div>
                          <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">RERA ID</span>
                          <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->RERA_no }}</span>
                        </div>
                      @endif
                      @if($prop->map_latitude || $prop->map_longitude)
                        <div>
                          <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Map Coordinates</span>
                          <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                            {{ $prop->map_latitude }}, {{ $prop->map_longitude }}
                          </span>
                        </div>
                      @endif
                    </div>
                  </div>

                  <!-- Specs Tab -->
                  <div id="tab-specs" class="prop-tab-content space-y-4 hidden">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                      <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Bedrooms</span>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->bedrooms ?? 0 }}</span>
                      </div>
                      <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Bathrooms</span>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->bathrooms ?? 0 }}</span>
                      </div>
                      <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Rooms</span>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->rooms ?? 0 }}</span>
                      </div>
                      <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Area / Size</span>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->size ? $prop->size . ' Sq. Ft.' : '—' }}</span>
                      </div>
                      <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Year Built</span>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->year_built ?? '—' }}</span>
                      </div>
                      @if($prop->available_from)
                        <div>
                          <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Available From</span>
                          <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($prop->available_from)->format('M d, Y') }}</span>
                        </div>
                      @endif
                      @if($prop->garage_size)
                        <div>
                          <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Garage Size</span>
                          <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->garage_size }}</span>
                        </div>
                      @endif
                      @if($prop->exterior_material)
                        <div>
                          <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Exterior Material</span>
                          <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->exterior_material }}</span>
                        </div>
                      @endif
                      @if($prop->structure_type)
                        <div>
                          <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Structure Type</span>
                          <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $prop->structure_type }}</span>
                        </div>
                      @endif
                    </div>
                  </div>

                  <!-- Amenities Tab -->
                  <div id="tab-features" class="prop-tab-content space-y-4 hidden">
                    @if($prop->features->isNotEmpty())
                      <div class="flex flex-wrap gap-2">
                        @foreach($prop->features->sortBy('name') as $feature)
                          <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800/40 dark:border-slate-800 dark:text-slate-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            {{ rtrim($feature->name, '.') }}
                          </span>
                        @endforeach
                      </div>
                    @else
                      <p class="text-xs text-gray-500 italic">No amenities specified for this property.</p>
                    @endif
                  </div>

                  <!-- Description Tab -->
                  <div id="tab-description" class="prop-tab-content space-y-4 hidden">
                    <p class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ $prop->description ?: 'No description text available.' }}</p>
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

@push('scripts')
<script>
function switchPropertyTab(evt, tabId) {
  // Hide all tab content elements
  document.querySelectorAll('.prop-tab-content').forEach(el => el.classList.add('hidden'));
  
  // Reset all buttons styling
  document.querySelectorAll('.prop-tab-btn').forEach(btn => {
    btn.classList.remove('border-brand-500', 'text-brand-500');
    btn.classList.add('border-transparent', 'text-gray-500');
  });

  // Show active tab content
  document.getElementById(tabId).classList.remove('hidden');
  
  // Highlight active button
  evt.currentTarget.classList.remove('border-transparent', 'text-gray-500');
  evt.currentTarget.classList.add('border-brand-500', 'text-brand-500');
}
</script>
@endpush
