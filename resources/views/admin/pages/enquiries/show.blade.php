@extends('admin.layouts.app')

@section('title', 'Lead Details')
@section('page', 'enquiries')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6" x-data="{
      openWhatsappModal: false,
      openCreateFollowUpModal: false,
      openEditFollowUpModal: false,
      openUpdateStatusModal: false,
      openTimelineModal: false,
      openNotesModal: false,
      activeTemplate: 'welcome',
      whatsappMessage: '',
      editFollowUp: {
        id: null,
        actionUrl: '',
        scheduled_at: '',
        reminder_before_minutes: 15,
        status: 'pending',
        outcome: '',
        call_notes: ''
      },
      openEditFollowUp(item) {
        this.editFollowUp.id = item.id;
        this.editFollowUp.actionUrl = '/admin/follow-ups/' + item.id;
        this.editFollowUp.scheduled_at = item.scheduled_at;
        this.editFollowUp.reminder_before_minutes = item.reminder_before_minutes || 15;
        this.editFollowUp.status = item.status || 'pending';
        this.editFollowUp.outcome = item.outcome || '';
        this.editFollowUp.call_notes = item.call_notes || '';
        this.openEditFollowUpModal = true;
      },
      init() {
        this.$watch('activeTemplate', (val) => {
          this.updatePreview();
        });
      },
      updatePreview() {
        if (this.activeTemplate === 'welcome') {
          const base = `{{ addslashes($settings['template_whatsapp_welcome'] ?? 'Dear {name}, thank you for registering with us.') }}`;
          this.whatsappMessage = base.replace('{name}', '{{ addslashes($enquiry->name) }}');
        } else if (this.activeTemplate === 'visit') {
          const base = `{{ addslashes($settings['template_site_visit_reminder'] ?? 'Reminder: Your site visit for {project} is scheduled.') }}`;
          this.whatsappMessage = base.replace('{name}', '{{ addslashes($enquiry->name) }}')
                                     .replace('{project}', '{{ addslashes($enquiry->property?->project?->name ?? 'our projects') }}');
        } else {
          this.whatsappMessage = '';
        }
      },
      sendWhatsapp() {
        const rawPhone = '{{ $enquiry->phone }}';
        const cleanPhone = rawPhone.replace(/[^0-9]/g, '');
        const msg = encodeURIComponent(this.whatsappMessage);
        window.open(`https://wa.me/${cleanPhone}?text=${msg}`, '_blank');
        this.openWhatsappModal = false;
      }
    }" x-init="updatePreview()">
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
            <div class="flex items-center gap-3">
              <button type="button" @click="openTimelineModal = true" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition cursor-pointer">
                <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                View Activity Timeline
              </button>
              <button type="button" @click="openUpdateStatusModal = true" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 cursor-pointer">Update Status / Notes</button>
            </div>
          </div>
        </div>

        <!-- 1. Lead Contact & Request Information (FIRST) -->
        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <h2 class="text-base font-bold text-gray-900 dark:text-white border-b border-gray-100 pb-3 mb-4 dark:border-gray-800">Request Information</h2>
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Name</span>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">{{ $enquiry->name }}</span>
            </div>
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Email</span>
              <a href="mailto:{{ $enquiry->email }}" class="text-sm font-medium text-brand-500 hover:underline block mt-1">{{ $enquiry->email }}</a>
            </div>
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Phone</span>
              @if($enquiry->phone)
                <div class="flex items-center gap-1.5 mt-1">
                  <a href="tel:{{ $enquiry->phone }}" class="text-sm font-medium text-brand-500 hover:underline">{{ $enquiry->phone }}</a>
                  <button type="button" @click="openWhatsappModal = true" class="inline-flex items-center justify-center p-1 rounded-md text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-500/10 transition" title="Send WhatsApp Message" style="color: #10b981; border: none; background: transparent;">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008 0c3.202.001 6.212 1.248 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.03-5.114-2.905-6.99C16.557 1.875 14.09 1.843 11.473.841 6.037.843 1.62 5.261 1.615 10.7c-.001 1.689.444 3.337 1.288 4.78l-.997 3.637 3.734-.979zm11.385-6.52c-.27-.135-1.602-.79-1.85-.88-.25-.09-.43-.135-.61.135-.18.27-.694.88-.85 1.055-.157.18-.314.2-.584.065-2.636-1.319-3.722-2.28-5.263-4.912-.13-.223-.01-.343.1-.453.1-.1.222-.26.333-.39.11-.13.15-.22.223-.37.074-.15.037-.28-.018-.39-.056-.113-.43-1.03-.59-1.41-.15-.37-.33-.32-.45-.32h-.39c-.135 0-.356.05-.54.25-.186.2-.71.694-.71 1.693s.73 1.96 1.03 2.1c.3.14 2.893 4.417 6.13 5.82 2.7.17 3.2.13 3.79-.1.583-.22 1.602-.65 1.83-1.28.226-.63.226-1.17.157-1.28-.07-.11-.27-.2-.54-.335z"/>
                    </svg>
                  </button>
                </div>
              @else
                <span class="text-sm text-gray-400 dark:text-gray-500 block mt-1">Not provided</span>
              @endif
            </div>
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Submitted At</span>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">{{ $enquiry->created_at->format('M d, Y h:i A') }}</span>
            </div>
          </div>

          <!-- Message -->
          <div class="mt-6 border-t border-gray-100 pt-4 dark:border-gray-800">
            <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Customer Message</span>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/40 p-4 rounded-xl whitespace-pre-line">{{ $enquiry->message ?: 'No message text provided.' }}</p>
          </div>
        </section>

        <!-- 2. Lead Status & Activity Overview (SECOND - Full Horizontal Grid) -->
        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4 dark:border-gray-800">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Lead Status & Activity</h2>
            @php($statusColors = [
              'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
              'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
              'contacted' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
              'interested' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
              'follow_up' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
              'site_visit' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400',
              'negotiation' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
              'won' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
              'lost' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            ])
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$enquiry->status] ?? 'bg-gray-100 text-gray-800' }}">
              {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
            </span>
          </div>

          @php($latestFollowUp = $enquiry->followUps->sortByDesc('updated_at')->first())
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Inquiry Purpose</span>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">
                {{ $enquiry->type === 'contact' ? 'Contact Form' : ($enquiry->type === 'info' ? 'Request Info' : ($enquiry->type === 'tour' ? 'Schedule Tour' : ($enquiry->type === 'agent' ? 'Agent Contact' : $enquiry->type))) }}
              </span>
            </div>
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Inquiry Category</span>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">
                {{ $enquiry->enq_for ? ucfirst($enquiry->enq_for) : 'N/A' }}
              </span>
            </div>
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Assigned Agent</span>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">
                {{ $enquiry->agent?->name ?? 'Unassigned' }}
              </span>
            </div>
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Last Action By</span>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">
                {{ $enquiry->actionBy?->name ?? $latestFollowUp?->creator?->name ?? 'System / Pending' }}
              </span>
            </div>
            <div>
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Last Action Time</span>
              <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">
                {{ $enquiry->performed_at ? \Carbon\Carbon::parse($enquiry->performed_at)->format('M d, Y h:i A') : ($latestFollowUp?->updated_at ? $latestFollowUp->updated_at->format('M d, Y h:i A') : 'N/A') }}
              </span>
            </div>
          </div>

          <div class="mt-6 border-t border-gray-100 pt-4 dark:border-gray-800">
            <div class="flex items-center justify-between mb-2">
              <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Internal Follow-up Note</span>
              <button type="button" @click="openNotesModal = true" class="inline-flex items-center gap-1 text-xs font-bold text-brand-500 hover:text-brand-600 cursor-pointer">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                View All Past Notes ({{ $allNotes->count() }})
              </button>
            </div>
            <p class="text-xs text-gray-600 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-900/40 p-3 rounded-xl border border-dashed border-gray-200 dark:border-gray-800">
              {{ $enquiry->note ?: ($latestFollowUp?->call_notes ?: 'No internal follow-up notes recorded yet.') }}
            </p>
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
            <!-- Follow-up Log History -->
            <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Follow-up Interaction History</h2>
                <button type="button" @click="openCreateFollowUpModal = true" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 hover:bg-brand-600 text-white px-3 py-2 text-xs font-semibold cursor-pointer">
                  + Schedule Follow-up
                </button>
              </div>
              
              @if($enquiry->followUps->isEmpty())
                <p class="text-xs text-gray-500 text-center py-6">No follow-up interactions logged yet for this lead.</p>
              @else
                <div class="overflow-x-auto">
                  <table class="min-w-full table-auto text-xs text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900/40 text-gray-500">
                      <tr>
                        <th class="px-4 py-3 font-semibold uppercase">Scheduled At</th>
                        <th class="px-4 py-3 font-semibold uppercase">Interacted By</th>
                        <th class="px-4 py-3 font-semibold uppercase">Outcome</th>
                        <th class="px-4 py-3 font-semibold uppercase">Status</th>
                        <th class="px-4 py-3 font-semibold uppercase">Notes</th>
                        <th class="px-4 py-3 font-semibold uppercase text-right">Action</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                      @foreach($enquiry->followUps as $item)
                        <tr>
                          <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                            {{ $item->scheduled_at ? $item->scheduled_at->format('d M Y, h:i A') : 'N/A' }}
                          </td>
                          <td class="px-4 py-3 text-gray-500">
                            {{ $item->creator?->name ?: 'System' }}
                          </td>
                          <td class="px-4 py-3 text-gray-500">
                            {{ $item->outcome ? str_replace('_', ' ', ucfirst($item->outcome)) : 'N/A' }}
                          </td>
                          <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded px-2 py-0.5 font-bold uppercase text-[9px] {{ $item->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                              {{ $item->status }}
                            </span>
                          </td>
                          <td class="px-4 py-3 text-gray-500 max-w-xs truncate" title="{{ $item->call_notes }}">
                            {{ $item->call_notes ?: 'N/A' }}
                          </td>
                          <td class="px-4 py-3 text-right">
                            <button type="button" @click="openEditFollowUp({
                              id: {{ $item->id }},
                              scheduled_at: '{{ $item->scheduled_at ? $item->scheduled_at->format('Y-m-d\TH:i') : '' }}',
                              reminder_before_minutes: {{ $item->reminder_before_minutes ?? 15 }},
                              status: '{{ $item->status }}',
                              outcome: '{{ $item->outcome }}',
                              call_notes: '{{ addslashes($item->call_notes ?? $item->note ?? '') }}'
                            })" class="inline-flex items-center justify-center p-1 rounded-md text-gray-500 hover:text-brand-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition cursor-pointer" title="Edit Follow-up">
                              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                              </svg>
                            </button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </section>
           </div>
        <!-- WhatsApp Template Modal -->
        <div x-show="openWhatsappModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex items-center justify-center p-4"
             style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
          
          <div x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95"
               class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-h-[85vh] overflow-y-auto flex flex-col" style="max-width: 460px;">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Send WhatsApp Message</h3>
              <button type="button" @click="openWhatsappModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
            </div>
            
            <div class="space-y-3">
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Select Template</label>
                <select x-model="activeTemplate" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="welcome">Welcome Message Template</option>
                  <option value="visit">Site Visit Reminder Template</option>
                  <option value="custom">Custom Blank Message</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Message Preview</label>
                <textarea x-model="whatsappMessage" rows="3" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white"></textarea>
              </div>

              <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="openWhatsappModal = false" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
                  Cancel
                </button>
                <button type="button" @click="sendWhatsapp()" class="rounded-lg px-4 py-1.5 text-xs font-semibold text-white hover:opacity-90 transition" style="background-color: #10b981; color: #ffffff; border: none;">
                  Send WhatsApp
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Create Follow-up Modal -->
        <div x-show="openCreateFollowUpModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex items-center justify-center p-4"
             style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
          
          <div x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95"
               class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-h-[85vh] overflow-y-auto flex flex-col" style="max-width: 460px;">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Schedule Follow-up Call</h3>
              <button type="button" @click="openCreateFollowUpModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
            </div>
            
            <form method="POST" action="{{ route('admin.follow-ups.store') }}" class="space-y-3">
              @csrf
              <input type="hidden" name="enquiry_id" value="{{ $enquiry->id }}">

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Scheduled Date & Time <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="scheduled_at" required value="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Reminder Before (Minutes)</label>
                <input type="number" name="reminder_before_minutes" min="0" value="15" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Initial Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="pending">Pending</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Outcome (Optional)</label>
                <select name="outcome" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select Outcome</option>
                  <option value="interested">Interested</option>
                  <option value="need_more_time">Need More Time</option>
                  <option value="not_interested">Not Interested</option>
                  <option value="booked_visit">Booked Site Visit</option>
                  <option value="no_response">No Response</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Discussion Notes</label>
                <textarea name="call_notes" rows="2.5" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Enter discussion notes..."></textarea>
              </div>

              <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="openCreateFollowUpModal = false" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
                  Cancel
                </button>
                <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-1.5 text-xs font-semibold text-white">
                  Schedule Call
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Edit Follow-up Modal -->
        <div x-show="openEditFollowUpModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex items-center justify-center p-4"
             style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
          
          <div x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95"
               class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-h-[85vh] overflow-y-auto flex flex-col" style="max-width: 460px;">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Edit Follow-up Details</h3>
              <button type="button" @click="openEditFollowUpModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
            </div>
            
            <form method="POST" :action="editFollowUp.actionUrl" class="space-y-3">
              @csrf
              @method('PUT')
              <input type="hidden" name="enquiry_id" value="{{ $enquiry->id }}">

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Scheduled Date & Time <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="scheduled_at" required x-model="editFollowUp.scheduled_at" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Reminder Before (Minutes)</label>
                <input type="number" name="reminder_before_minutes" min="0" x-model="editFollowUp.reminder_before_minutes" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" x-model="editFollowUp.status" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="pending">Pending</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Outcome</label>
                <select name="outcome" x-model="editFollowUp.outcome" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select Outcome</option>
                  <option value="interested">Interested</option>
                  <option value="need_more_time">Need More Time</option>
                  <option value="not_interested">Not Interested</option>
                  <option value="booked_visit">Booked Site Visit</option>
                  <option value="no_response">No Response</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Discussion Notes</label>
                <textarea name="call_notes" x-model="editFollowUp.call_notes" rows="2.5" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white"></textarea>
              </div>

              <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="openEditFollowUpModal = false" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
                  Cancel
                </button>
                <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-1.5 text-xs font-semibold text-white">
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Update Lead Status & Notes Modal -->
        <div x-show="openUpdateStatusModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex items-center justify-center p-4"
             style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
          
          <div x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95"
               class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-h-[85vh] overflow-y-auto flex flex-col" style="max-width: 460px;">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Update Lead Status & Notes</h3>
              <button type="button" @click="openUpdateStatusModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
            </div>
            
            <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" class="space-y-3">
              @csrf
              @method('PUT')

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Lifecycle Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  @foreach(['draft', 'new', 'contacted', 'interested', 'follow_up', 'site_visit', 'negotiation', 'won', 'lost'] as $st)
                    <option value="{{ $st }}" {{ $enquiry->status === $st ? 'selected' : '' }}>
                      {{ ucfirst(str_replace('_', ' ', $st)) }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned Agent</label>
                <select name="agent_id" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Unassigned</option>
                  @foreach($agents as $ag)
                    <option value="{{ $ag->id }}" {{ $enquiry->agent_id == $ag->id ? 'selected' : '' }}>
                      {{ $ag->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Internal Follow-up Note</label>
                <textarea name="note" rows="3" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Write internal activity note...">{{ old('note', $enquiry->note) }}</textarea>
              </div>

              <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="openUpdateStatusModal = false" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
                  Cancel
                </button>
                <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-1.5 text-xs font-semibold text-white">
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Lead Activity & Lifecycle Timeline Modal Popup -->
        <div x-show="openTimelineModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex items-center justify-center p-4"
             style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
          
          <div x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95"
               class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-h-[85vh] flex flex-col" style="max-width: 500px;">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
              <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-50 text-brand-500 dark:bg-brand-500/10">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                  <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Lead Activity & Lifecycle Timeline</h3>
                  <span class="text-[11px] text-gray-400 font-normal">Chronological event history</span>
                </div>
              </div>
              <button type="button" @click="openTimelineModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
            </div>

            <div class="overflow-y-auto max-h-[60vh] pr-1 space-y-3">
              @if($activityTimeline->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">No timeline events recorded yet.</p>
              @else
                <div class="relative pl-5 border-l border-gray-200 dark:border-gray-800 space-y-3 ml-2 py-1">
                  @foreach($activityTimeline as $event)
                    <div class="relative">
                      <div class="absolute -left-[27px] top-1 flex h-4 w-4 items-center justify-center rounded-full {{ $event['icon_bg'] }} text-white text-[9px]">
                        &bull;
                      </div>

                      <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3 text-xs dark:border-gray-800 dark:bg-gray-900/30">
                        <div class="flex items-center justify-between gap-2 mb-1 border-b border-gray-100 pb-1 dark:border-gray-800">
                          <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900 dark:text-white text-xs">{{ $event['title'] }}</span>
                            <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[9px] font-semibold {{ $event['badge_color'] }}">
                              {{ $event['badge'] }}
                            </span>
                          </div>
                          <span class="text-[10px] text-gray-400 font-normal shrink-0">
                            {{ $event['timestamp'] ? \Carbon\Carbon::parse($event['timestamp'])->format('d M Y, h:i A') : '' }}
                          </span>
                        </div>

                        @if(!empty($event['author']))
                          <span class="text-[10px] text-gray-400 block mb-1">By {{ $event['author'] }}</span>
                        @endif

                        @if(!empty($event['description']))
                          <p class="text-xs text-gray-600 dark:text-gray-400 font-normal leading-relaxed mt-1">
                            {{ $event['description'] }}
                          </p>
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>

            <div class="mt-3 border-t border-gray-100 pt-2.5 flex justify-end dark:border-gray-800">
              <button type="button" @click="openTimelineModal = false" class="rounded-lg bg-gray-100 px-3.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 transition cursor-pointer">Close</button>
            </div>
          </div>
        </div>

        <!-- All Past Notes Log Modal Popup -->
        <div x-show="openNotesModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex items-center justify-center p-4"
             style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
          
          <div x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95"
               class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-h-[85vh] flex flex-col" style="max-width: 500px;">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
              <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-50 text-brand-500 dark:bg-brand-500/10">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                  <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Historical Notes Log</h3>
                  <span class="text-[11px] text-gray-400 font-normal">All discussion notes & status updates</span>
                </div>
              </div>
              <button type="button" @click="openNotesModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
            </div>

            <div class="overflow-y-auto max-h-[60vh] pr-1 space-y-3">
              @if($allNotes->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">No historical notes recorded yet.</p>
              @else
                @foreach($allNotes as $nt)
                  <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-3 text-xs dark:border-gray-800 dark:bg-gray-900/40">
                    
                    <div class="flex items-start justify-between gap-3 mb-2 pb-1.5 border-b border-gray-100 dark:border-gray-800/80">
                      <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold tracking-wide uppercase {{ $nt['source_badge'] }}">
                          {{ $nt['source'] }}
                        </span>
                        @if(!empty($nt['author']))
                          <span class="text-[11px] text-gray-500 font-normal">by {{ $nt['author'] }}</span>
                        @endif
                      </div>
                      <span class="text-[11px] text-gray-400 font-normal shrink-0">
                        {{ $nt['timestamp'] ? \Carbon\Carbon::parse($nt['timestamp'])->format('d M Y, h:i A') : '' }}
                      </span>
                    </div>

                    <p class="text-xs text-gray-700 dark:text-gray-300 font-normal leading-relaxed whitespace-pre-line px-0.5">
                      {{ $nt['note'] }}
                    </p>
                  </div>
                @endforeach
              @endif
            </div>

            <div class="mt-3 border-t border-gray-100 pt-2.5 flex justify-end dark:border-gray-800">
              <button type="button" @click="openNotesModal = false" class="rounded-lg bg-gray-100 px-3.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 transition cursor-pointer">Close</button>
            </div>
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
