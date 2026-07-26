@extends('admin.layouts.app')

@section('title', 'Lead Details - ' . $enquiry->name)
@section('page', 'enquiries')

@section('content')
  @include('admin.partials.admin-header')

  <main class="min-h-screen bg-gray-50/50 dark:bg-gray-950 p-4 md:p-6" x-data="{
    activeTab: 'overview',
    openWhatsappModal: false,
    openCreateFollowUpModal: false,
    openEditFollowUpModal: false,
    openUpdateStatusModal: false,
    openTimelineModal: false,
    openNotesModal: false,
    openConvertToClientModal: false,
    openEditRequirementsModal: false,
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

    <div class="mx-auto max-w-(--breakpoint-2xl) space-y-3.5">

      <!-- Top Action Bar & Breadcrumb -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 font-medium">
          <a href="{{ route('admin.enquiries.index') }}" class="hover:text-brand-500 flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Leads
          </a>
          <span>/</span>
          <span class="text-gray-900 dark:text-white font-semibold">Lead Details</span>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <!-- Convert to Client Button -->
          <button type="button" @click="openConvertToClientModal = true" class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-brand-600 transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Convert to Client
          </button>

          <!-- Edit Lead Button -->
          <a href="{{ route('admin.enquiries.edit', $enquiry) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 transition">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            Edit Lead
          </a>

          <!-- Update Status Button -->
          <button type="button" @click="openUpdateStatusModal = true" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white p-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 transition" title="More Actions">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
          </button>
        </div>
      </div>

      <!-- INITIAL DETAIL CARD (HomeSphere Style Header Card) -->
      @php
        $words = explode(' ', $enquiry->name);
        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
        
        $reqBhk = $enquiry->bhk ?: ($enquiry->property?->bedrooms ? $enquiry->property->bedrooms . ' BHK' : '3 BHK');
        $reqType = $enquiry->preferred_type ?: ($enquiry->property?->categories?->first()?->name ?: 'Apartment');
        $reqLoc = $enquiry->location ?: ($enquiry->property?->locality ?: ($enquiry->property?->city?->name ?: 'Gurgaon'));
        $requirementSubtitle = "Looking for {$reqBhk} {$reqType} in {$reqLoc}";

        $statusClasses = [
          'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
          'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-400',
          'contacted' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400',
          'interested' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-400',
          'follow_up' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-400',
          'site_visit' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-400',
          'negotiation' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-400',
          'won' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400',
          'lost' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-400',
        ];
      @endphp

      <div class="rounded-2xl border border-gray-200/80 bg-white pl-1.5 pr-3.5 py-4 sm:pl-2 sm:pr-4 sm:py-4.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 sm:gap-5">
          
          <!-- Left: Lead Avatar & Identity Info -->
          <div class="flex items-start gap-3">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 font-bold text-base dark:bg-indigo-950 dark:text-indigo-300 shadow-xs mt-1">
              {{ $initials }}
            </div>

            <div>
              <div class="flex items-start gap-1">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">{{ $enquiry->name }}</h1>
                <span class="inline-flex items-center rounded-full font-bold uppercase tracking-wider ml-0.5 {{ $statusClasses[$enquiry->status] ?? 'bg-gray-100 text-gray-800' }}" style="font-size: 10px; line-height: 1; padding: 2.5px 6.5px; position: relative; top: -5px;">
                  {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                </span>
              </div>

              <p class="mt-0.5 text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $requirementSubtitle }}</span>
              </p>

              <!-- Contact Chips -->
              <div class="mt-2 text-xs text-gray-600 dark:text-gray-400" style="display: flex; flex-wrap: wrap; align-items: center; column-gap: 14px; row-gap: 6px; margin-left: -3px;">
                @if($enquiry->phone)
                  <div class="flex items-center" style="display: inline-flex; align-items: center;">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <a href="tel:{{ $enquiry->phone }}" class="hover:text-brand-500 font-medium ml-1.5">{{ $enquiry->phone }}</a>
                  </div>
                @endif

                @if($enquiry->email)
                  <div class="flex items-center" style="display: inline-flex; align-items: center;">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <a href="mailto:{{ $enquiry->email }}" class="hover:text-brand-500 font-medium ml-1.5">{{ $enquiry->email }}</a>
                  </div>
                @endif

                @if($enquiry->location)
                  <div class="flex items-center" style="display: inline-flex; align-items: center;">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="ml-1.5">{{ $enquiry->location }}</span>
                  </div>
                @endif

                @if($enquiry->phone)
                  <button type="button" @click="openWhatsappModal = true" class="inline-flex items-center text-emerald-500 hover:text-emerald-600 transition" title="Send WhatsApp">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008 0c3.202.001 6.212 1.248 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.03-5.114-2.905-6.99C16.557 1.875 14.09 1.843 11.473.841 6.037.843 1.62 5.261 1.615 10.7c-.001 1.689.444 3.337 1.288 4.78l-.997 3.637 3.734-.979zm11.385-6.52c-.27-.135-1.602-.79-1.85-.88-.25-.09-.43-.135-.61.135-.18.27-.694.88-.85 1.055-.157.18-.314.2-.584.065-2.636-1.319-3.722-2.28-5.263-4.912-.13-.223-.01-.343.1-.453.1-.1.222-.26.333-.39.11-.13.15-.22.223-.37.074-.15.037-.28-.018-.39-.056-.113-.43-1.03-.59-1.41-.15-.37-.33-.32-.45-.32h-.39c-.135 0-.356.05-.54.25-.186.2-.71.694-.71 1.693s.73 1.96 1.03 2.1c.3.14 2.893 4.417 6.13 5.82 2.7.17 3.2.13 3.79-.1.583-.22 1.602-.65 1.83-1.28.226-.63.226-1.17.157-1.28-.07-.11-.27-.2-.54-.335z"/></svg>
                  </button>
                @endif
              </div>
            </div>
          </div>

          <!-- Right: Key Meta Values (All 5 items in a single non-wrapping horizontal row, moved down & left) -->
          <div class="pt-2.5 lg:pt-2 border-t lg:border-t-0 border-gray-100 dark:border-gray-800 shrink-0" style="display: flex; flex-wrap: nowrap; align-items: flex-start; column-gap: 18px; margin-right: 35px; margin-top: 14px;">
            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Lead ID</span>
              <span class="text-xs font-bold text-gray-900 dark:text-white mt-0.5 block whitespace-nowrap">#L-{{ sprintf('%04d', $enquiry->id) }}</span>
            </div>

            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Lead Source</span>
              <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block whitespace-nowrap">{{ $enquiry->lead_source ?: 'Website' }}</span>
            </div>

            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Created On</span>
              <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block whitespace-nowrap">{{ $enquiry->created_at->format('d M, Y h:i A') }}</span>
            </div>

            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Assigned To</span>
              <div class="flex items-center gap-1.5 mt-0.5 whitespace-nowrap">
                <div class="flex h-4.5 w-4.5 items-center justify-center rounded-full bg-brand-100 text-brand-700 text-[9px] font-bold dark:bg-brand-900/40 dark:text-brand-300">
                  {{ $enquiry->agent ? strtoupper(substr($enquiry->agent->name, 0, 1)) : 'U' }}
                </div>
                <span class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $enquiry->agent?->name ?: 'Unassigned' }}</span>
              </div>
            </div>

            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Last Contact</span>
              @php($latestFollowUp = $enquiry->followUps->sortByDesc('updated_at')->first())
              <span class="text-xs font-semibold text-gray-900 dark:text-white mt-0.5 block whitespace-nowrap">
                {{ $latestFollowUp?->updated_at ? $latestFollowUp->updated_at->format('d M, Y h:i A') : ($enquiry->performed_at ? \Carbon\Carbon::parse($enquiry->performed_at)->format('d M, Y h:i A') : 'N/A') }}
              </span>
            </div>
          </div>

        </div>
      </div>

      <!-- MAIN 2-COLUMN RESPONSIVE LAYOUT (Left Column 837px, Right Sidebar 250px side-by-side) -->
      <div class="flex flex-row flex-nowrap gap-4 items-start pb-4">
        
        <!-- LEFT COLUMN (837px width, reference layout) -->
        <div class="shrink-0 space-y-4" style="width: 837px; min-width: 837px;">

          <!-- MULTIPLE INFO METRIC CARDS (Fixed w-[133px], gap-2 in a w-[837px] container, single horizontal line) -->
          <div class="w-[837px] flex flex-nowrap items-center gap-2">
            <!-- 1. Requirement Card -->
            <div class="rounded-xl border border-gray-200/80 bg-white shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[133px]" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 7px; width: 133px; min-width: 133px;">
              <div class="rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400" style="display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; flex-shrink: 0;">
                <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
              </div>
              <div>
                <span class="block font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9.5px; line-height: 1;">Requirement</span>
                <span class="font-bold text-gray-800 dark:text-white whitespace-nowrap block truncate max-w-[95px]" style="font-size: 11.5px; line-height: 1.2; margin-top: 5px;" title="{{ $reqBhk }} {{ $reqType }}">
                  {{ $reqBhk }} {{ $reqType }}
                </span>
              </div>
            </div>

            <!-- 2. Budget Card -->
            <div class="rounded-xl border border-gray-200/80 bg-white shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[133px]" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 7px; width: 133px; min-width: 133px;">
              <div class="rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; flex-shrink: 0;">
                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div>
                <span class="block font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Budget</span>
                <span class="font-bold text-gray-800 dark:text-white whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;">
                  {{ $enquiry->budget ? '₹' . $enquiry->budget : '₹1.2 Cr' }}
                </span>
              </div>
            </div>

            <!-- 3. Preferred Location Card -->
            <div class="rounded-xl border border-gray-200/80 bg-white shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[133px]" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 7px; width: 133px; min-width: 133px;">
              <div class="rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; flex-shrink: 0;">
                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <div>
                <span class="block font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Location</span>
                <span class="font-bold text-gray-800 dark:text-white whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;" title="{{ $reqLoc }}">
                  {{ $reqLoc }}
                </span>
              </div>
            </div>

            <!-- 4. Possession Card -->
            <div class="rounded-xl border border-gray-200/80 bg-white shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[133px]" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 7px; width: 133px; min-width: 133px;">
              <div class="rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; flex-shrink: 0;">
                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <div>
                <span class="block font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Possession</span>
                <span class="font-bold text-gray-800 dark:text-white whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;">
                  Within 6 Mos
                </span>
              </div>
            </div>

            <!-- 5. Purpose Card -->
            <div class="rounded-xl border border-gray-200/80 bg-white shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[133px]" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 7px; width: 133px; min-width: 133px;">
              <div class="rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; flex-shrink: 0;">
                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <div>
                <span class="block font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Purpose</span>
                <span class="font-bold text-gray-800 dark:text-white whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;">
                  {{ $enquiry->enq_for ? ucfirst($enquiry->enq_for) : 'Self Use' }}
                </span>
              </div>
            </div>

            <!-- 6. Status Card -->
            <div class="rounded-xl border border-gray-200/80 bg-white shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[133px]" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 7px; width: 133px; min-width: 133px;">
              <div class="rounded-lg bg-cyan-50 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; flex-shrink: 0;">
                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
              </div>
              <div>
                <span class="block font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Status</span>
                <span class="font-bold text-gray-800 dark:text-white whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;">
                  {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                </span>
              </div>
            </div>
          </div>

          <!-- TAB NAVIGATION BAR (Clean Title Case, aligned with cards below) -->
          <div class="border-b border-gray-200/80 dark:border-gray-800 pb-0.5 mb-4 w-[837px]">
            <div class="flex overflow-x-auto no-scrollbar gap-2 sm:gap-3 items-center">
              
              <button type="button" @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-brand-500 text-brand-600 dark:text-brand-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium'" class="border-b-2 transition whitespace-nowrap cursor-pointer text-xs py-1.5 px-2.5">
                Overview
              </button>

              <button type="button" @click="activeTab = 'activities'" :class="activeTab === 'activities' ? 'border-brand-500 text-brand-600 dark:text-brand-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium'" class="border-b-2 transition whitespace-nowrap cursor-pointer text-xs py-1.5 px-2.5">
                Activities / Timeline
              </button>

              <button type="button" @click="activeTab = 'notes'" :class="activeTab === 'notes' ? 'border-brand-500 text-brand-600 dark:text-brand-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium'" class="border-b-2 transition whitespace-nowrap cursor-pointer text-xs py-1.5 px-2.5">
                Notes ({{ $allNotes->count() }})
              </button>

              <button type="button" @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'border-brand-500 text-brand-600 dark:text-brand-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium'" class="border-b-2 transition whitespace-nowrap cursor-pointer text-xs py-1.5 px-2.5">
                Tasks (1)
              </button>

              <button type="button" @click="activeTab = 'follow_ups'" :class="activeTab === 'follow_ups' ? 'border-brand-500 text-brand-600 dark:text-brand-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium'" class="border-b-2 transition whitespace-nowrap cursor-pointer text-xs py-1.5 px-2.5">
                Follow Ups ({{ $enquiry->followUps->count() }})
              </button>

              <button type="button" @click="activeTab = 'site_visits'" :class="activeTab === 'site_visits' ? 'border-brand-500 text-brand-600 dark:text-brand-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium'" class="border-b-2 transition whitespace-nowrap cursor-pointer text-xs py-1.5 px-2.5">
                Site Visits ({{ $enquiry->siteVisits->count() }})
              </button>

              <button type="button" @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'border-brand-500 text-brand-600 dark:text-brand-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium'" class="border-b-2 transition whitespace-nowrap cursor-pointer text-xs py-1.5 px-2.5">
                Documents ({{ $documents->count() }})
              </button>

            </div>
          </div>

              <!-- 1. OVERVIEW TAB -->
              <div x-show="activeTab === 'overview'" class="space-y-6">
                
                <!-- Overview Cards Stack (Horizontal side-by-side compact 275px cards with gap-2 between cards, total 837px) -->
                <div class="flex flex-nowrap items-start gap-2 w-[837px]">
                  
                  <!-- Lead Information Card -->
                  <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 dark:border-gray-800 dark:bg-gray-900 space-y-3 shadow-2xs w-[275px]" style="width: 275px; min-width: 275px;">
                    <div class="border-b border-gray-100 pb-2 dark:border-gray-800">
                      <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Lead Information</h3>
                    </div>

                    <table class="w-full text-xs">
                      <tbody>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Full Name</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->name }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Phone</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">
                            <div class="flex items-center gap-1">
                              <span>{{ $enquiry->phone ?: 'N/A' }}</span>
                              @if($enquiry->phone)
                                <a href="tel:{{ $enquiry->phone }}" class="text-gray-400 hover:text-brand-500 ml-1" title="Call">
                                  <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </a>
                                <button type="button" @click="openWhatsappModal = true" class="text-emerald-500 hover:text-emerald-600 transition" title="WhatsApp">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008 0c3.202.001 6.212 1.248 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.953-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.03-5.114-2.905-6.99C16.557 1.875 14.09 1.843 11.473.841 6.037.843 1.62 5.261 1.615 10.7c-.001 1.689.444 3.337 1.288 4.78l-.997 3.637 3.734-.979zm11.385-6.52c-.27-.135-1.602-.79-1.85-.88-.25-.09-.43-.135-.61.135-.18.27-.694.88-.85 1.055-.157.18-.314.2-.584.065-2.636-1.319-3.722-2.28-5.263-4.912-.13-.223-.01-.343.1-.453.1-.1.222-.26.333-.39.11-.13.15-.22.223-.37.074-.15.037-.28-.018-.39-.056-.113-.43-1.03-.59-1.41-.15-.37-.33-.32-.45-.32h-.39c-.135 0-.356.05-.54.25-.186.2-.71.694-.71 1.693s.73 1.96 1.03 2.1c.3.14 2.893 4.417 6.13 5.82 2.7.17 3.2.13 3.79-.1.583-.22 1.602-.65 1.83-1.28.226-.63.226-1.17.157-1.28-.07-.11-.27-.2-.54-.335z"/></svg>
                                </button>
                              @endif
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Email</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top break-all text-[11px]">{{ $enquiry->email }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Alt Phone</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">+91 98765 43210</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Location</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->location ?: 'Gurgaon' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Lead Source</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->lead_source ?: 'Website' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Referred By</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">N/A</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Pref. Areas</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $reqLoc }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Requirement Details Card -->
                  <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 dark:border-gray-800 dark:bg-gray-900 space-y-3 shadow-2xs w-[275px]" style="width: 275px; min-width: 275px;">
                    <div class="border-b border-gray-100 pb-2 dark:border-gray-800">
                      <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Requirement Details</h3>
                    </div>

                    <table class="w-full text-xs">
                      <tbody>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Type</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->preferred_type ?: 'Apartment' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">BHK</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->bhk ?: '3 BHK' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Carpet Area</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->carpet_area ?: '1400 - 1800 sq.ft.' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Floor Pref.</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->floor_preference ?: 'Any' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Furnishing</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->furnishing ?: 'Semi Furnished' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Amenities</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top leading-snug break-words text-[11px]">Gym, Clubhouse, Backup, 24x7 Security</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Preferences</td>
                          <td class="py-1 font-bold text-gray-900 dark:text-white align-top leading-snug break-words text-[11px]">{{ $enquiry->other_preferences ?: 'Near Metro, Connectivity' }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Budget & Financial Card -->
                  <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 dark:border-gray-800 dark:bg-gray-900 space-y-3 shadow-2xs w-[275px]" style="width: 275px; min-width: 275px;">
                    <div class="border-b border-gray-100 pb-2 dark:border-gray-800">
                      <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Budget & Financial</h3>
                    </div>

                    <table class="w-full text-xs">
                      <tbody>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Budget</td>
                          <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">₹{{ $enquiry->budget ?: '1.2 Cr' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Budget Type</td>
                          <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->budget_type ?: 'Negotiable' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Payment Mode</td>
                          <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->payment_mode ?: 'Loan + Self' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Loan Req.</td>
                          <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">Yes</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Loan Amount</td>
                          <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->loan_amount ? '₹' . number_format($enquiry->loan_amount) : '₹80 L' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Down Payment</td>
                          <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->down_payment ? '₹' . number_format($enquiry->down_payment) : '₹40 L' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Expected ROI</td>
                          <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">N/A</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </div>

                <!-- Communication History Table (Resized to 837px matching Overview reference width) -->
                <div class="rounded-xl border border-gray-200/80 bg-white p-4 shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[837px] space-y-3" style="width: 837px; min-width: 837px;">
                  <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Communication History</h3>
                    <button type="button" @click="activeTab = 'activities'" class="text-xs font-semibold text-brand-500 hover:underline">View Timeline</button>
                  </div>
                  
                  <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                      <thead class="bg-gray-50 dark:bg-gray-800/40 text-gray-400 font-semibold uppercase text-[10px]">
                        <tr>
                          <th class="px-3 py-2">Type</th>
                          <th class="px-3 py-2">From</th>
                          <th class="px-3 py-2">Subject / Preview</th>
                          <th class="px-3 py-2">Date & Time</th>
                          <th class="px-3 py-2 text-right">Channel</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                          <td class="px-3 py-2.5 font-medium flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span> Email
                          </td>
                          <td class="px-3 py-2.5 text-gray-500">{{ $enquiry->agent?->name ?: 'Staff' }}</td>
                          <td class="px-3 py-2.5 max-w-xs truncate">{{ $reqBhk }} options in {{ $reqLoc }}</td>
                          <td class="px-3 py-2.5 text-gray-400">{{ $enquiry->created_at->format('d M, Y h:i A') }}</td>
                          <td class="px-3 py-2.5 text-right font-semibold text-gray-500">Email</td>
                        </tr>
                        <tr>
                          <td class="px-3 py-2.5 font-medium flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span> WhatsApp
                          </td>
                          <td class="px-3 py-2.5 text-gray-500">{{ $enquiry->name }}</td>
                          <td class="px-3 py-2.5 max-w-xs truncate">Looking forward to seeing site visit options</td>
                          <td class="px-3 py-2.5 text-gray-400">{{ $enquiry->updated_at->format('d M, Y h:i A') }}</td>
                          <td class="px-3 py-2.5 text-right font-semibold text-gray-500">WhatsApp</td>
                        </tr>
                        <tr>
                          <td class="px-3 py-2.5 font-medium flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-purple-500"></span> Call
                          </td>
                          <td class="px-3 py-2.5 text-gray-500">{{ $enquiry->agent?->name ?: 'Staff' }}</td>
                          <td class="px-3 py-2.5 max-w-xs truncate">Follow-up call discussed requirements and timeline</td>
                          <td class="px-3 py-2.5 text-gray-400">{{ $latestFollowUp?->updated_at ? $latestFollowUp->updated_at->format('d M, Y h:i A') : 'N/A' }}</td>
                          <td class="px-3 py-2.5 text-right font-semibold text-gray-500">Call</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>

              <!-- 2. ACTIVITIES TAB -->
              <div x-show="activeTab === 'activities'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">All Activity Log History</h3>
                </div>
                
                @if($activityTimeline->isEmpty())
                  <p class="text-xs text-gray-400 italic text-center py-6">No timeline activity logged.</p>
                @else
                  <div class="relative pl-6 border-l border-gray-200 dark:border-gray-800 space-y-4 ml-2 py-2">
                    @foreach($activityTimeline as $ev)
                      <div class="relative">
                        <div class="absolute -left-[31px] top-1 flex h-4 w-4 items-center justify-center rounded-full {{ $ev['icon_bg'] }} text-white text-[9px]">
                          &bull;
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3 text-xs dark:border-gray-800 dark:bg-gray-900/30">
                          <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="font-bold text-gray-900 dark:text-white">{{ $ev['title'] }}</span>
                            <span class="text-[10px] text-gray-400 font-medium">{{ $ev['timestamp'] ? \Carbon\Carbon::parse($ev['timestamp'])->format('d M Y, h:i A') : '' }}</span>
                          </div>
                          @if(!empty($ev['author']))
                            <span class="text-[10px] text-gray-400 block mb-1">By {{ $ev['author'] }}</span>
                          @endif
                          <p class="text-xs text-gray-600 dark:text-gray-400 font-normal leading-relaxed">{{ $ev['description'] }}</p>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

              <!-- 3. NOTES TAB -->
              <div x-show="activeTab === 'notes'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Internal Discussion Notes</h3>
                  <button type="button" @click="openNotesModal = true" class="text-xs font-semibold text-brand-500 hover:underline cursor-pointer">+ Add New Note</button>
                </div>

                <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" class="space-y-3">
                  @csrf
                  @method('PUT')
                  <textarea name="note" rows="2.5" class="w-full rounded-xl border border-gray-200 bg-white p-3 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Write internal activity note..."></textarea>
                  <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-1.5 text-xs font-semibold text-white">Save Note</button>
                  </div>
                </form>

                <div class="space-y-3 pt-2">
                  @foreach($allNotes as $nt)
                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-3 text-xs dark:border-gray-800 dark:bg-gray-900/40">
                      <div class="flex items-center justify-between gap-2 mb-1.5 pb-1 border-b border-gray-100 dark:border-gray-800">
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[9px] font-bold uppercase {{ $nt['source_badge'] }}">{{ $nt['source'] }}</span>
                        <span class="text-[10px] text-gray-400">{{ $nt['timestamp'] ? \Carbon\Carbon::parse($nt['timestamp'])->format('d M Y, h:i A') : '' }}</span>
                      </div>
                      <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $nt['note'] }}</p>
                    </div>
                  @endforeach
                </div>
              </div>

              <!-- 4. TASKS TAB -->
              <div x-show="activeTab === 'tasks'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Assigned Tasks</h3>
                  <button type="button" @click="openCreateFollowUpModal = true" class="text-xs font-semibold text-brand-500 hover:underline cursor-pointer">+ Add Task</button>
                </div>

                <div class="space-y-2">
                  <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3 text-xs flex items-center justify-between dark:border-gray-800 dark:bg-gray-900/30">
                    <div class="flex items-center gap-3">
                      <input type="checkbox" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                      <div>
                        <span class="font-bold text-gray-900 dark:text-white block">Share property options</span>
                        <span class="text-[11px] text-gray-400 block mt-0.5">Due: {{ date('d M, Y 11:00 AM', strtotime('+1 day')) }}</span>
                      </div>
                    </div>
                    <span class="inline-flex items-center rounded-md bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 px-2 py-0.5 text-[10px] font-semibold">Pending</span>
                  </div>
                </div>
              </div>

              <!-- 5. FOLLOW UPS TAB -->
              <div x-show="activeTab === 'follow_ups'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Follow-up History</h3>
                  <button type="button" @click="openCreateFollowUpModal = true" class="text-xs font-semibold text-brand-500 hover:underline cursor-pointer">+ Schedule Follow-up</button>
                </div>

                @if($enquiry->followUps->isEmpty())
                  <p class="text-xs text-gray-400 text-center py-6">No follow-ups logged yet.</p>
                @else
                  <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                      <thead class="bg-gray-50 dark:bg-gray-800/40 text-gray-400 font-semibold uppercase text-[10px]">
                        <tr>
                          <th class="px-3 py-2">Scheduled At</th>
                          <th class="px-3 py-2">Interacted By</th>
                          <th class="px-3 py-2">Outcome</th>
                          <th class="px-3 py-2">Status</th>
                          <th class="px-3 py-2">Notes</th>
                          <th class="px-3 py-2 text-right">Action</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                        @foreach($enquiry->followUps as $item)
                          <tr>
                            <td class="px-3 py-2.5 font-medium">{{ $item->scheduled_at ? $item->scheduled_at->format('d M Y, h:i A') : 'N/A' }}</td>
                            <td class="px-3 py-2.5 text-gray-500">{{ $item->creator?->name ?: 'Staff' }}</td>
                            <td class="px-3 py-2.5 text-gray-500">{{ $item->outcome ? str_replace('_', ' ', ucfirst($item->outcome)) : 'N/A' }}</td>
                            <td class="px-3 py-2.5">
                              <span class="inline-flex items-center rounded px-2 py-0.5 font-bold uppercase text-[9px] {{ $item->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $item->status }}
                              </span>
                            </td>
                            <td class="px-3 py-2.5 text-gray-500 max-w-xs truncate">{{ $item->call_notes ?: 'N/A' }}</td>
                            <td class="px-3 py-2.5 text-right">
                              <button type="button" @click="openEditFollowUp({
                                id: {{ $item->id }},
                                scheduled_at: '{{ $item->scheduled_at ? $item->scheduled_at->format('Y-m-d\TH:i') : '' }}',
                                reminder_before_minutes: {{ $item->reminder_before_minutes ?? 15 }},
                                status: '{{ $item->status }}',
                                outcome: '{{ $item->outcome }}',
                                call_notes: '{{ addslashes($item->call_notes ?? $item->note ?? '') }}'
                              })" class="text-brand-500 hover:underline">Edit</button>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @endif
              </div>

              <!-- 6. SITE VISITS TAB -->
              <div x-show="activeTab === 'site_visits'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Scheduled Site Visits</h3>
                </div>

                @if($enquiry->siteVisits->isEmpty())
                  <p class="text-xs text-gray-400 text-center py-6">No site visits scheduled yet.</p>
                @else
                  <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                      <thead class="bg-gray-50 dark:bg-gray-800/40 text-gray-400 font-semibold uppercase text-[10px]">
                        <tr>
                          <th class="px-3 py-2">Scheduled At</th>
                          <th class="px-3 py-2">Executive</th>
                          <th class="px-3 py-2">Project / Property</th>
                          <th class="px-3 py-2">Status</th>
                          <th class="px-3 py-2">Feedback</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                        @foreach($enquiry->siteVisits as $sv)
                          <tr>
                            <td class="px-3 py-2.5 font-medium">{{ $sv->scheduled_at ? \Carbon\Carbon::parse($sv->scheduled_at)->format('d M Y, h:i A') : 'N/A' }}</td>
                            <td class="px-3 py-2.5 text-gray-500">{{ $sv->assignedExecutive?->name ?: 'Executive' }}</td>
                            <td class="px-3 py-2.5 text-gray-800 dark:text-gray-200 font-semibold">{{ $sv->project?->name ?: ($sv->property?->title ?: 'Property') }}</td>
                            <td class="px-3 py-2.5">
                              <span class="inline-flex items-center rounded px-2 py-0.5 font-bold uppercase text-[9px] {{ $sv->status === 'visited' ? 'bg-emerald-100 text-emerald-800' : 'bg-cyan-100 text-cyan-800' }}">
                                {{ $sv->status }}
                              </span>
                            </td>
                            <td class="px-3 py-2.5 text-gray-500">{{ $sv->feedback ?: 'N/A' }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @endif
              </div>

              <!-- 7. BUDGET & FINANCIAL TAB -->
              <div x-show="activeTab === 'financial'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Budget & Financial Breakdown</h3>
                </div>
                  <table class="w-full text-xs">
                    <tbody>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Budget Range</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">₹{{ $enquiry->budget ?: '1.2 Cr' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Budget Type</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->budget_type ?: 'Negotiable' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Payment Mode</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->payment_mode ?: 'Loan + Self' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Loan Required</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">Yes</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Estimated Loan Amount</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->loan_amount ? '₹' . number_format($enquiry->loan_amount) : '₹80 L' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Down Payment Planned</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->down_payment ? '₹' . number_format($enquiry->down_payment) : '₹40 L' }}</td>
                      </tr>
                    </tbody>
                  </table>
              </div>

              <!-- 8. REQUIREMENT DETAILS TAB -->
              <div x-show="activeTab === 'requirement'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Full Client Requirement Specifications</h3>
                </div>
                  <table class="w-full text-xs">
                    <tbody>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Property Type</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->preferred_type ?: 'Apartment' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">BHK Configuration</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->bhk ?: '3 BHK' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Carpet Area Range</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->carpet_area ?: '1400 - 1800 sq.ft.' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Floor Preference</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->floor_preference ?: 'Any' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Furnishing Status</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->furnishing ?: 'Semi Furnished' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Required Amenities</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">Gym, Clubhouse, Power Backup, 24x7 Security, Parking</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Other Preferences</td>
                        <td class="py-1.5 font-bold text-gray-900 dark:text-white align-top">{{ $enquiry->other_preferences ?: 'Near Metro, Good Connectivity, Reputed Builder' }}</td>
                      </tr>
                    </tbody>
                  </table>
              </div>

              <!-- 9. DOCUMENTS TAB -->
              <div x-show="activeTab === 'documents'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Customer Documents</h3>
                </div>

                @if($documents->isEmpty())
                  <div class="text-center py-8 border border-dashed border-gray-200 rounded-xl dark:border-gray-800">
                    <svg class="w-8 h-8 text-gray-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <p class="text-xs text-gray-400 mt-2 font-medium">No documents attached yet for this customer profile.</p>
                  </div>
                @else
                  <div class="space-y-2">
                    @foreach($documents as $doc)
                      <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3 text-xs flex items-center justify-between dark:border-gray-800 dark:bg-gray-900/30">
                        <div class="flex items-center gap-3">
                          <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                          <div>
                            <span class="font-bold text-gray-900 dark:text-white block">{{ $doc->file_name }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">{{ number_format(($doc->file_size ?? 0)/1024, 1) }} KB &bull; {{ $doc->category ?: 'General' }}</span>
                          </div>
                        </div>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-brand-500 font-semibold hover:underline">Download</a>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

        </div>

        <!-- RIGHT SIDEBAR COLUMN (250px width, sits right next to Budget & Financial card, zero horizontal scroll) -->
        <div class="shrink-0 flex flex-col gap-4 w-[250px]" style="width: 250px; min-width: 250px;">

          <!-- LEAD SCORE SUMMARY CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-3 cursor-pointer hover:border-emerald-500 transition" @click="openTimelineModal = true" title="Click to view score details">
            <div class="border-b border-gray-100 pb-2.5 dark:border-gray-800 flex items-center justify-between">
              <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Lead Score Analysis</h3>
              <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full dark:bg-emerald-900/40 dark:text-emerald-400">High Potential</span>
            </div>
            
            <div class="flex items-center gap-3.5 pt-1">
              <!-- Score Ring -->
              <div class="relative flex items-center justify-center shrink-0" style="width: 48px; height: 48px; position: relative;">
                <svg style="width: 48px; height: 48px;" class="transform -rotate-90">
                  <circle cx="24" cy="24" r="19" stroke="currentColor" stroke-width="3" class="text-gray-100 dark:text-gray-800" fill="transparent" />
                  <circle cx="24" cy="24" r="19" stroke="currentColor" stroke-width="3" class="text-emerald-500" fill="transparent"
                          stroke-dasharray="119.3"
                          stroke-dashoffset="{{ 119.3 - (119.3 * $leadScoreInfo['score']) / 100 }}"
                          stroke-linecap="round" />
                </svg>
                <span class="absolute font-extrabold text-gray-900 dark:text-white text-xs" style="position: absolute;">{{ $leadScoreInfo['score'] }}</span>
              </div>

              <!-- Left Nurturing Green Line & Text -->
              <div style="border-left: 3px solid #10b981; padding-left: 10px;">
                <span class="font-bold text-emerald-600 dark:text-emerald-400 text-xs block" style="display: block; font-weight: 700; color: #059669;">
                  {{ $leadScoreInfo['comment'] }}
                </span>
                <p class="text-[10.5px] text-gray-500 dark:text-gray-400 font-medium leading-tight mt-0.5" style="font-size: 10.5px; line-height: 1.3; color: #6b7280; margin-top: 2px;">
                  {{ $leadScoreInfo['subtitle'] }}
                </p>
              </div>
            </div>
          </div>

          <!-- 1. TASKS SUMMARY CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
              <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Tasks (1)</h3>
              <button type="button" @click="activeTab = 'tasks'" class="text-[11px] font-semibold text-brand-500 hover:underline">View All</button>
            </div>

            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3 text-xs space-y-1.5 dark:border-gray-800 dark:bg-gray-900/30">
              <div class="flex items-start gap-2.5">
                <input type="checkbox" class="mt-0.5 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                <div>
                  <span class="font-bold text-gray-900 dark:text-white block">Share property options</span>
                  <span class="text-[10px] text-gray-400 font-medium block">Due: {{ date('d M, Y 11:00 AM', strtotime('+1 day')) }}</span>
                </div>
              </div>
              <div class="flex justify-end">
                <span class="inline-flex items-center rounded-md bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 px-1.5 py-0.5 text-[9px] font-semibold">Pending</span>
              </div>
            </div>
          </div>

          <!-- 3. FOLLOW UPS SUMMARY CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
              <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Follow Ups ({{ $enquiry->followUps->count() }})</h3>
              <button type="button" @click="activeTab = 'follow_ups'" class="text-[11px] font-semibold text-brand-500 hover:underline">View All</button>
            </div>

            <div class="space-y-2">
              <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3 text-xs space-y-1 dark:border-gray-800 dark:bg-gray-900/30">
                <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                  <span>Follow up call</span>
                </div>
                <span class="text-[10px] text-gray-400 block font-medium">{{ date('25 May, 2026 11:00 AM') }}</span>
              </div>

              <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3 text-xs space-y-1 dark:border-gray-800 dark:bg-gray-900/30">
                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008 0c3.202.001 6.212 1.248 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.03-5.114-2.905-6.99C16.557 1.875 14.09 1.843 11.473.841 6.037.843 1.62 5.261 1.615 10.7c-.001 1.689.444 3.337 1.288 4.78l-.997 3.637 3.734-.979zm11.385-6.52c-.27-.135-1.602-.79-1.85-.88-.25-.09-.43-.135-.61.135-.18.27-.694.88-.85 1.055-.157.18-.314.2-.584.065-2.636-1.319-3.722-2.28-5.263-4.912-.13-.223-.01-.343.1-.453.1-.1.222-.26.333-.39.11-.13.15-.22.223-.37.074-.15.037-.28-.018-.39-.056-.113-.43-1.03-.59-1.41-.15-.37-.33-.32-.45-.32h-.39c-.135 0-.356.05-.54.25-.186.2-.71.694-.71 1.693s.73 1.96 1.03 2.1c.3.14 2.893 4.417 6.13 5.82 2.7.17 3.2.13 3.79-.1.583-.22 1.602-.65 1.83-1.28.226-.63.226-1.17.157-1.28-.07-.11-.27-.2-.54-.335z"/></svg>
                  <span>WhatsApp follow up</span>
                </div>
                <span class="text-[10px] text-gray-400 block font-medium">{{ date('26 May, 2026 05:00 PM') }}</span>
              </div>
            </div>
          </div>

          <!-- 4. ASSOCIATED PROPERTY / DEAL CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
              <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Deals / Interested Unit</h3>
              <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full dark:bg-emerald-900/40 dark:text-emerald-400">Active</span>
            </div>

            @if($enquiry->property)
              @php($prop = $enquiry->property)
              <div class="rounded-xl border border-gray-100 p-3 space-y-2 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30">
                <div class="flex items-center gap-3">
                  @php($primaryImage = $prop->images?->firstWhere('is_primary', true) ?? $prop->images?->first())
                  @if($primaryImage)
                    <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($primaryImage->path) }}" alt="{{ $prop->title }}" class="h-12 w-12 rounded-lg object-cover">
                  @else
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-200 dark:bg-gray-800 text-gray-400">
                      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                  @endif
                  <div>
                    <span class="font-bold text-xs text-gray-900 dark:text-white block line-clamp-1">{{ $prop->title }}</span>
                    <span class="text-xs font-bold text-brand-500 block mt-0.5">{{ $prop->price ? '₹' . number_format($prop->price) : '₹1.15 Cr' }}</span>
                  </div>
                </div>
              </div>
            @else
              <div class="text-center py-4 border border-dashed border-gray-100 rounded-xl dark:border-gray-800">
                <span class="text-xs text-gray-400 font-medium">No specific property linked.</span>
              </div>
            @endif
          </div>

          <!-- 5. DOCUMENTS SUMMARY CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
              <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Documents ({{ $documents->count() }})</h3>
              <button type="button" @click="activeTab = 'documents'" class="text-[11px] font-semibold text-brand-500 hover:underline">View All</button>
            </div>

            @if($documents->isEmpty())
              <p class="text-xs text-gray-400 italic text-center py-2">No documents attached.</p>
            @else
              <div class="space-y-2">
                @foreach($documents->take(3) as $doc)
                  <div class="flex items-center justify-between text-xs p-2 rounded-lg bg-gray-50 dark:bg-gray-800/40">
                    <span class="font-semibold text-gray-800 dark:text-gray-200 truncate max-w-[150px]">{{ $doc->file_name }}</span>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-brand-500 font-semibold hover:underline">Download</a>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

        </div>

      </div>

    </div>

    <!-- MODALS -->

    <!-- CONVERT TO CLIENT MODAL -->
    <div x-show="openConvertToClientModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex items-center justify-center p-4"
         style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px);">
      
      <div class="w-full rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-w-md">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
          <h3 class="text-base font-bold text-gray-900 dark:text-white">Convert Lead to Client Profile</h3>
          <button type="button" @click="openConvertToClientModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold">&times;</button>
        </div>

        <p class="text-xs text-gray-600 dark:text-gray-300 mt-3 leading-relaxed">
          This action will convert lead <strong>{{ $enquiry->name }}</strong> into a registered <strong>Customer / Client</strong> profile with their contact details and preferences.
        </p>

        <form method="POST" action="{{ route('admin.enquiries.convert-to-client', $enquiry) }}" class="mt-4 space-y-4">
          @csrf
          <div class="flex justify-end gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
            <button type="button" @click="openConvertToClientModal = false" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
              Cancel
            </button>
            <button type="submit" class="rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
              Confirm &amp; Convert
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- EDIT REQUIREMENTS MODAL -->
    <div x-show="openEditRequirementsModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex items-center justify-center p-4"
         style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px);">
      
      <div class="w-full rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-w-xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800 mb-4">
          <h3 class="text-sm font-bold text-gray-900 dark:text-white">Edit Client Requirement &amp; Financial Specs</h3>
          <button type="button" @click="openEditRequirementsModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" class="space-y-4 text-xs">
          @csrf
          @method('PUT')

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Property Type</label>
              <input type="text" name="preferred_type" value="{{ old('preferred_type', $enquiry->preferred_type) }}" placeholder="e.g. Apartment, Villa" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">BHK</label>
              <input type="text" name="bhk" value="{{ old('bhk', $enquiry->bhk) }}" placeholder="e.g. 3 BHK" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Carpet Area</label>
              <input type="text" name="carpet_area" value="{{ old('carpet_area', $enquiry->carpet_area) }}" placeholder="e.g. 1400 - 1800 sq.ft." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Floor Preference</label>
              <input type="text" name="floor_preference" value="{{ old('floor_preference', $enquiry->floor_preference) }}" placeholder="e.g. High Floor" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Furnishing</label>
              <input type="text" name="furnishing" value="{{ old('furnishing', $enquiry->furnishing) }}" placeholder="e.g. Semi Furnished" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Budget</label>
              <input type="text" name="budget" value="{{ old('budget', $enquiry->budget) }}" placeholder="e.g. 1.2 Cr" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Budget Type</label>
              <input type="text" name="budget_type" value="{{ old('budget_type', $enquiry->budget_type) }}" placeholder="e.g. Negotiable, Fixed" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Mode</label>
              <input type="text" name="payment_mode" value="{{ old('payment_mode', $enquiry->payment_mode) }}" placeholder="e.g. Loan + Self" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Loan Required</label>
              <select name="loan_required" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                <option value="1" {{ $enquiry->loan_required ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$enquiry->loan_required ? 'selected' : '' }}>No</option>
              </select>
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Loan Amount (₹)</label>
              <input type="number" name="loan_amount" value="{{ old('loan_amount', $enquiry->loan_amount) }}" placeholder="8000000" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div class="col-span-2">
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Down Payment (₹)</label>
              <input type="number" name="down_payment" value="{{ old('down_payment', $enquiry->down_payment) }}" placeholder="4000000" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div class="col-span-2">
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Other Preferences</label>
              <textarea name="other_preferences" rows="2" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">{{ old('other_preferences', $enquiry->other_preferences) }}</textarea>
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
            <button type="button" @click="openEditRequirementsModal = false" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
              Cancel
            </button>
            <button type="submit" class="rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-xs font-semibold text-white">
              Save Specifications
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- WHATSAPP TEMPLATE MODAL -->
    <div x-show="openWhatsappModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex items-center justify-center p-4"
         style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px);">
      
      <div class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-w-md">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Send WhatsApp Message</h3>
          <button type="button" @click="openWhatsappModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
        </div>
        
        <div class="space-y-3 text-xs">
          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Select Template</label>
            <select x-model="activeTemplate" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              <option value="welcome">Welcome Message Template</option>
              <option value="visit">Site Visit Reminder Template</option>
              <option value="custom">Custom Blank Message</option>
            </select>
          </div>

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Message Preview</label>
            <textarea x-model="whatsappMessage" rows="3" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white"></textarea>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
            <button type="button" @click="openWhatsappModal = false" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
              Cancel
            </button>
            <button type="button" @click="sendWhatsapp()" class="rounded-lg px-4 py-1.5 font-semibold text-white bg-emerald-500 hover:bg-emerald-600 transition">
              Send WhatsApp
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- UPDATE LEAD STATUS MODAL -->
    <div x-show="openUpdateStatusModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex items-center justify-center p-4"
         style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px);">
      
      <div class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-w-md">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Update Lead Status &amp; Notes</h3>
          <button type="button" @click="openUpdateStatusModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
        </div>
        
        <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" class="space-y-3 text-xs">
          @csrf
          @method('PUT')

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Lifecycle Status</label>
            <select name="status" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              @foreach(['draft', 'new', 'contacted', 'interested', 'follow_up', 'site_visit', 'negotiation', 'won', 'lost'] as $st)
                <option value="{{ $st }}" {{ $enquiry->status === $st ? 'selected' : '' }}>
                  {{ ucfirst(str_replace('_', ' ', $st)) }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned Agent</label>
            <select name="agent_id" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              <option value="">Unassigned</option>
              @foreach($agents as $ag)
                <option value="{{ $ag->id }}" {{ $enquiry->agent_id == $ag->id ? 'selected' : '' }}>
                  {{ $ag->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Lead Score (0 - 100)</label>
            <input type="number" name="lead_score" min="0" max="100" value="{{ old('lead_score', $enquiry->lead_score) }}" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
          </div>

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Internal Follow-up Note</label>
            <textarea name="note" rows="2.5" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Write internal activity note...">{{ old('note', $enquiry->note) }}</textarea>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
            <button type="button" @click="openUpdateStatusModal = false" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
              Cancel
            </button>
            <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-1.5 font-semibold text-white">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- CREATE FOLLOW-UP MODAL -->
    <div x-show="openCreateFollowUpModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex items-center justify-center p-4"
         style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px);">
      
      <div class="w-full rounded-2xl bg-white p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-w-md">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Schedule Follow-up Call</h3>
          <button type="button" @click="openCreateFollowUpModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
        </div>
        
        <form method="POST" action="{{ route('admin.follow-ups.store') }}" class="space-y-3 text-xs">
          @csrf
          <input type="hidden" name="enquiry_id" value="{{ $enquiry->id }}">

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Scheduled Date &amp; Time <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="scheduled_at" required value="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
          </div>

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Discussion Notes</label>
            <textarea name="call_notes" rows="2.5" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Enter notes..."></textarea>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
            <button type="button" @click="openCreateFollowUpModal = false" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
              Cancel
            </button>
            <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-1.5 font-semibold text-white">
              Schedule Call
            </button>
          </div>
        </form>
      </div>
    </div>

  </main>
@endsection
