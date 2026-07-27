@extends('admin.layouts.app')

@section('title', 'Lead Details - ' . $enquiry->name)
@section('page', 'enquiries')

@section('content')
  @include('admin.partials.admin-header')

  <main class="min-h-screen bg-gray-50/50 dark:bg-gray-950 p-4 md:p-6" x-data="{
    activeTab: 'overview',
    openActionMenu: false,
    openDeleteLeadModal: false,
    openCreateTaskModal: false,
    openEditLeadModal: false,
    openWhatsappModal: false,
    openCreateFollowUpModal: false,
    openEditFollowUpModal: false,
    openUpdateStatusModal: false,
    openTimelineModal: false,
    openNotesModal: false,
    openConvertToClientModal: false,
    openUploadDocumentModal: false,
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
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-[1150px]" style="width: 1150px;">
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

          <!-- Burger Action Menu -->
          <div class="relative" @click.outside="openActionMenu = false">
            <button type="button" @click="openActionMenu = !openActionMenu" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white p-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 transition cursor-pointer" title="More Actions">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="openActionMenu" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-xl py-2 z-50 text-xs w-[230px]"
                 style="width: 230px; min-width: 230px;">
              
              <!-- 1. Edit Lead -->
              <button type="button" @click="openEditLeadModal = true; openActionMenu = false" class="w-full text-left flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 font-medium transition whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span class="whitespace-nowrap">Edit Lead</span>
              </button>

              <!-- 2. Add Follow Up -->
              <button type="button" @click="openCreateFollowUpModal = true; openActionMenu = false" class="w-full text-left flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 font-medium transition whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span class="whitespace-nowrap">Add Follow Up</span>
              </button>

              <!-- 3. Add Task -->
              <button type="button" @click="openCreateTaskModal = true; openActionMenu = false" class="w-full text-left flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 font-medium transition whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span class="whitespace-nowrap">Add Task</span>
              </button>

              <!-- 4. Add Document -->
              <button type="button" @click="openUploadDocumentModal = true; openActionMenu = false" class="w-full text-left flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 font-medium transition whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m3-3H9"/></svg>
                <span class="whitespace-nowrap">Add Document</span>
              </button>

              <!-- 5. Schedule Site Visit -->
              <a href="{{ route('admin.site-visits.create', ['enquiry_id' => $enquiry->id]) }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 font-medium transition whitespace-nowrap">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="whitespace-nowrap">Schedule Site Visit</span>
              </a>

              <!-- 5. Add Note / Update Status -->
              <button type="button" @click="openUpdateStatusModal = true; openActionMenu = false" class="w-full text-left flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 font-medium transition whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span class="whitespace-nowrap">Add Note / Update Status</span>
              </button>

              <!-- 6. Send Email -->
              @if($enquiry->email)
                <a href="mailto:{{ $enquiry->email }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 font-medium transition whitespace-nowrap">
                  <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  <span class="whitespace-nowrap">Send Email</span>
                </a>
              @endif

              <!-- 7. Send WhatsApp -->
              @if($enquiry->phone)
                <button type="button" @click="openWhatsappModal = true; openActionMenu = false" class="w-full text-left flex items-center gap-3 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 font-medium transition whitespace-nowrap cursor-pointer">
                  <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008 0c3.202.001 6.212 1.248 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.953-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.03-5.114-2.905-6.99C16.557 1.875 14.09 1.843 11.473.841 6.037.843 1.62 5.261 1.615 10.7c-.001 1.689.444 3.337 1.288 4.78l-.997 3.637 3.734-.979zm11.385-6.52c-.27-.135-1.602-.79-1.85-.88-.25-.09-.43-.135-.61.135-.18.27-.694.88-.85 1.055-.157.18-.314.2-.584.065-2.636-1.319-3.722-2.28-5.263-4.912-.13-.223-.01-.343.1-.453.1-.1.222-.26.333-.39.11-.13.15-.22.223-.37.074-.15.037-.28-.018-.39-.056-.113-.43-1.03-.59-1.41-.15-.37-.33-.32-.45-.32h-.39c-.135 0-.356.05-.54.25-.186.2-.71.694-.71 1.693s.73 1.96 1.03 2.1c.3.14 2.893 4.417 6.13 5.82 2.7.17 3.2.13 3.79-.1.583-.22 1.602-.65 1.83-1.28.226-.63.226-1.17.157-1.28-.07-.11-.27-.2-.54-.335z"/></svg>
                  <span class="whitespace-nowrap">Send WhatsApp</span>
                </button>
              @endif

              <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

              <!-- 9. Delete Lead -->
              <button type="button" @click="openDeleteLeadModal = true; openActionMenu = false" class="w-full text-left flex items-center gap-3 px-4 py-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 font-semibold transition whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span class="whitespace-nowrap">Delete Lead</span>
              </button>

            </div>
          </div>
        </div>
      </div>

      <!-- INITIAL DETAIL CARD (HomeSphere Style Header Card) -->
      @php
        if (!function_exists('formatIndianCurrency')) {
            function formatIndianCurrency($value, $prefix = '₹') {
                if ($value === null || $value === '' || $value === false) {
                    return 'N/A';
                }
                
                $cleanVal = preg_replace('/[^\d.]/', '', (string)$value);
                if (!is_numeric($cleanVal) || floatval($cleanVal) <= 0) {
                    return $value;
                }
                
                $num = floatval($cleanVal);
                
                if ($num >= 10000000) {
                    $cr = $num / 10000000;
                    $formatted = ($cr == floor($cr)) ? number_format($cr, 0) : rtrim(rtrim(number_format($cr, 2), '0'), '.');
                    return $prefix . $formatted . ' Cr';
                } elseif ($num >= 100000) {
                    $lac = $num / 100000;
                    $formatted = ($lac == floor($lac)) ? number_format($lac, 0) : rtrim(rtrim(number_format($lac, 2), '0'), '.');
                    return $prefix . $formatted . ' L';
                } elseif ($num >= 1000) {
                    $k = $num / 1000;
                    $formatted = ($k == floor($k)) ? number_format($k, 0) : rtrim(rtrim(number_format($k, 2), '0'), '.');
                    return $prefix . $formatted . ' K';
                }
                
                return $prefix . number_format($num);
            }
        }

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

      <div class="rounded-2xl border border-gray-200/80 bg-white pl-1.5 pr-3.5 py-4 sm:pl-2 sm:pr-4 sm:py-4.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[1150px]" style="width: 1150px;">
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
              <span class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-0.5 block whitespace-nowrap">#L-{{ sprintf('%04d', $enquiry->id) }}</span>
            </div>

            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Lead Source</span>
              <span class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-0.5 block whitespace-nowrap">{{ $enquiry->lead_source ?: 'Website' }}</span>
            </div>

            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Created On</span>
              <span class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-0.5 block whitespace-nowrap">{{ $enquiry->created_at->format('d M, Y h:i A') }}</span>
            </div>

            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Assigned To</span>
              <div class="flex items-center gap-1.5 mt-0.5 whitespace-nowrap">
                <div class="flex h-4.5 w-4.5 items-center justify-center rounded-full bg-brand-100 text-brand-700 text-[9px] font-semibold dark:bg-brand-900/40 dark:text-brand-300">
                  {{ $enquiry->agent ? strtoupper(substr($enquiry->agent->name, 0, 1)) : 'U' }}
                </div>
                <span class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate">{{ $enquiry->agent?->name ?: 'Unassigned' }}</span>
              </div>
            </div>

            <div>
              <span class="block text-xs font-normal text-gray-400 whitespace-nowrap">Last Contact</span>
              @php($latestFollowUp = $enquiry->followUps->sortByDesc('updated_at')->first())
              <span class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-0.5 block whitespace-nowrap">
                {{ $latestFollowUp?->updated_at ? $latestFollowUp->updated_at->format('d M, Y h:i A') : ($enquiry->performed_at ? \Carbon\Carbon::parse($enquiry->performed_at)->format('d M, Y h:i A') : 'N/A') }}
              </span>
            </div>
          </div>

        </div>
      </div>

      <!-- MAIN 2-COLUMN RESPONSIVE LAYOUT (Left Column 837px, Right Sidebar 297px side-by-side, Total 1150px) -->
      <div class="flex flex-row flex-nowrap gap-4 items-start pb-4 w-[1150px]" style="width: 1150px; margin-top: 10.5px !important;">
        
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
                <span class="block font-medium text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9.5px; line-height: 1;">Requirement</span>
                <span class="font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap block truncate max-w-[95px]" style="font-size: 11.5px; line-height: 1.2; margin-top: 5px;" title="{{ $reqBhk }} {{ $reqType }}">
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
                <span class="block font-medium text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Budget</span>
                <span class="font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;">
                  {{ formatIndianCurrency($enquiry->budget ?: 12000000) }}
                </span>
              </div>
            </div>

            <!-- 3. Preferred Location Card -->
            <div class="rounded-xl border border-gray-200/80 bg-white shadow-2xs dark:border-gray-800 dark:bg-gray-900 w-[133px]" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 7px; width: 133px; min-width: 133px;">
              <div class="rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400" style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; flex-shrink: 0;">
                <svg style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <div>
                <span class="block font-medium text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Location</span>
                <span class="font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;" title="{{ $reqLoc }}">
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
                <span class="block font-medium text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Possession</span>
                <span class="font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;">
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
                <span class="block font-medium text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Purpose</span>
                <span class="font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;">
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
                <span class="block font-medium text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 9px; line-height: 1;">Status</span>
                <span class="font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap block truncate max-w-[80px]" style="font-size: 11px; line-height: 1.2; margin-top: 4px;">
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
              <div x-show="activeTab === 'overview'" class="space-y-3">
                
                <!-- Overview Cards Stack (Horizontal side-by-side compact 275px cards with gap-2 between cards, total 837px) -->
                <div class="flex flex-nowrap items-start gap-2 w-[837px]">
                  
                  <!-- Lead Information Card -->
                  <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 dark:border-gray-800 dark:bg-gray-900 space-y-3 shadow-2xs w-[275px]" style="width: 275px; min-width: 275px;">
                    <div class="border-b border-gray-100 pb-2 dark:border-gray-800 flex items-center justify-between">
                      <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Lead Information</h3>
                      <button type="button" @click="openEditLeadModal = true" class="text-gray-400 hover:text-brand-500 transition cursor-pointer" title="Edit Lead Details">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                      </button>
                    </div>

                    <table class="w-full text-xs">
                      <tbody>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Full Name</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->name }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Phone</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">
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
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top break-all text-[11px]">{{ $enquiry->email }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Alt Phone</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">+91 98765 43210</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Location</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->location ?: 'Gurgaon' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Lead Source</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->lead_source ?: 'Website' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Property</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">
                            @if($enquiry->property_id)
                              <a href="{{ route('admin.properties.edit', $enquiry->property_id) }}" class="text-brand-600 dark:text-brand-400 hover:underline font-semibold">
                                {{ $enquiry->property_name ?: ($enquiry->property?->title ?? 'N/A') }}
                              </a>
                            @else
                              {{ $enquiry->property_name ?: 'N/A' }}
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Referred By</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">N/A</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Pref. Areas</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $reqLoc }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Requirement Details Card -->
                  <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 dark:border-gray-800 dark:bg-gray-900 space-y-3 shadow-2xs w-[275px]" style="width: 275px; min-width: 275px;">
                    <div class="border-b border-gray-100 pb-2 dark:border-gray-800">
                      <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Requirement Details</h3>
                    </div>

                    <table class="w-full text-xs">
                      <tbody>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Type</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->preferred_type ?: 'Apartment' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">BHK</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->bhk ?: '3 BHK' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Carpet Area</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->carpet_area ?: '1400 - 1800 sq.ft.' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Floor Pref.</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->floor_preference ?: 'Any' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Furnishing</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->furnishing ?: 'Semi Furnished' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Amenities</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top leading-snug break-words text-[11px]">Gym, Clubhouse, Backup, 24x7 Security</td>
                        </tr>
                        <tr>
                          <td class="py-1 pr-2 text-gray-400 font-normal whitespace-nowrap align-top w-20">Preferences</td>
                          <td class="py-1 font-medium text-gray-700 dark:text-gray-300 align-top leading-snug break-words text-[11px]">{{ $enquiry->other_preferences ?: 'Near Metro, Connectivity' }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Budget & Financial Card -->
                  <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 dark:border-gray-800 dark:bg-gray-900 space-y-3 shadow-2xs w-[275px]" style="width: 275px; min-width: 275px;">
                    <div class="border-b border-gray-100 pb-2 dark:border-gray-800">
                      <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Budget & Financial</h3>
                    </div>

                    <table class="w-full text-xs">
                      <tbody>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Budget</td>
                          <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ formatIndianCurrency($enquiry->budget ?: 12000000) }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Budget Type</td>
                          <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->budget_type ?: 'Negotiable' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Payment Mode</td>
                          <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->payment_mode ?: 'Loan + Self' }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Loan Req.</td>
                          <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">Yes</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Loan Amount</td>
                          <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ formatIndianCurrency($enquiry->loan_amount ?: 8000000) }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Down Payment</td>
                          <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ formatIndianCurrency($enquiry->down_payment ?: 4000000) }}</td>
                        </tr>
                        <tr>
                          <td class="py-1.5 pr-3 text-gray-400 font-normal whitespace-nowrap align-top w-24">Expected ROI</td>
                          <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">N/A</td>
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
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Assigned Lead Tasks ({{ $enquiry->tasks->count() }})</h3>
                  <button type="button" @click="openCreateTaskModal = true" class="inline-flex items-center gap-1 text-xs font-semibold text-brand-500 hover:underline cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Task</span>
                  </button>
                </div>

                @if($enquiry->tasks->isEmpty())
                  <div class="text-center py-8 space-y-3">
                    <div class="mx-auto w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400">
                      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">No tasks created yet for this lead.</p>
                    <button type="button" @click="openCreateTaskModal = true" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 hover:bg-brand-600 px-3.5 py-1.5 text-xs font-semibold text-white cursor-pointer transition">
                      + Create First Task
                    </button>
                  </div>
                @else
                  <div class="space-y-2.5">
                    @foreach($enquiry->tasks as $taskItem)
                      <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-3.5 text-xs flex items-center justify-between dark:border-gray-800 dark:bg-gray-900/40 hover:border-gray-300 dark:hover:border-gray-700 transition">
                        <div class="flex items-start gap-3 min-w-0 pr-4">
                          <form method="POST" action="{{ route('admin.tasks.toggle', $taskItem) }}" class="mt-0.5 shrink-0">
                            @csrf
                            <input type="checkbox" onchange="this.form.submit()" {{ $taskItem->status === 'completed' ? 'checked' : '' }} class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 cursor-pointer w-4 h-4">
                          </form>

                          <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                              <span class="font-bold text-gray-900 dark:text-white text-xs {{ $taskItem->status === 'completed' ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">
                                {{ $taskItem->title }}
                              </span>

                              <!-- Priority Badge -->
                              @if($taskItem->priority === 'urgent')
                                <span class="rounded bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-400 px-1.5 py-0.5 text-[9px] font-bold uppercase">Urgent</span>
                              @elseif($taskItem->priority === 'high')
                                <span class="rounded bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-400 px-1.5 py-0.5 text-[9px] font-bold uppercase">High</span>
                              @elseif($taskItem->priority === 'medium')
                                <span class="rounded bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-400 px-1.5 py-0.5 text-[9px] font-semibold uppercase">Medium</span>
                              @else
                                <span class="rounded bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 px-1.5 py-0.5 text-[9px] font-medium uppercase">Low</span>
                              @endif
                            </div>

                            @if($taskItem->description)
                              <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">{{ $taskItem->description }}</p>
                            @endif

                            <div class="flex items-center gap-3 text-[10px] text-gray-400 font-medium flex-wrap pt-0.5">
                              <span>Due: {{ $taskItem->due_date ? $taskItem->due_date->format('d M, Y h:i A') : 'No due date' }}</span>
                              <span>&bull;</span>
                              <span>Assigned: {{ $taskItem->assignedAgent?->name ?: 'Unassigned' }}</span>
                              @if($taskItem->followUp)
                                <span>&bull;</span>
                                <span class="text-brand-500">Linked to Follow-up #{{ $taskItem->follow_up_id }}</span>
                              @endif
                            </div>
                          </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                          <!-- Status Tag -->
                          @if($taskItem->status === 'completed')
                            <span class="inline-flex items-center rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400 px-2 py-0.5 text-[10px] font-bold uppercase">Completed</span>
                          @elseif($taskItem->status === 'in_progress')
                            <span class="inline-flex items-center rounded-md bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-400 px-2 py-0.5 text-[10px] font-bold uppercase">In Progress</span>
                          @elseif($taskItem->status === 'cancelled')
                            <span class="inline-flex items-center rounded-md bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 px-2 py-0.5 text-[10px] font-bold uppercase">Cancelled</span>
                          @else
                            <span class="inline-flex items-center rounded-md bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-400 px-2 py-0.5 text-[10px] font-bold uppercase">Pending</span>
                          @endif

                          <!-- Delete Task -->
                          <form method="POST" action="{{ route('admin.tasks.destroy', $taskItem) }}" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-rose-500 transition p-1 cursor-pointer" title="Delete Task">
                              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                          </form>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif
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
                  <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Budget & Financial Breakdown</h3>
                </div>
                  <table class="w-full text-xs">
                    <tbody>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Budget Range</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ formatIndianCurrency($enquiry->budget ?: 12000000) }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Budget Type</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->budget_type ?: 'Negotiable' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Payment Mode</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->payment_mode ?: 'Loan + Self' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Loan Required</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">Yes</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Estimated Loan Amount</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ formatIndianCurrency($enquiry->loan_amount ?: 8000000) }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Down Payment Planned</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ formatIndianCurrency($enquiry->down_payment ?: 4000000) }}</td>
                      </tr>
                    </tbody>
                  </table>
              </div>

              <!-- 8. REQUIREMENT DETAILS TAB -->
              <div x-show="activeTab === 'requirement'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200">Full Client Requirement Specifications</h3>
                </div>
                  <table class="w-full text-xs">
                    <tbody>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Property Type</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->preferred_type ?: 'Apartment' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">BHK Configuration</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->bhk ?: '3 BHK' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Carpet Area Range</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->carpet_area ?: '1400 - 1800 sq.ft.' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Floor Preference</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->floor_preference ?: 'Any' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Furnishing Status</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->furnishing ?: 'Semi Furnished' }}</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Required Amenities</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">Gym, Clubhouse, Power Backup, 24x7 Security, Parking</td>
                      </tr>
                      <tr>
                        <td class="py-1.5 pr-4 text-gray-400 font-normal whitespace-nowrap align-top w-36">Other Preferences</td>
                        <td class="py-1.5 font-medium text-gray-700 dark:text-gray-300 align-top">{{ $enquiry->other_preferences ?: 'Near Metro, Good Connectivity, Reputed Builder' }}</td>
                      </tr>
                    </tbody>
                  </table>
              </div>

              <!-- 9. DOCUMENTS TAB -->
              <div x-show="activeTab === 'documents'" class="rounded-xl border border-gray-200/80 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 space-y-4 shadow-2xs w-[837px]" style="width: 837px; min-width: 837px;">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 dark:border-gray-800">
                  <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Customer Documents</h3>
                  <button type="button" @click="openUploadDocumentModal = true" class="text-xs font-semibold text-brand-500 hover:underline cursor-pointer">+ Upload Document</button>
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

        <!-- RIGHT SIDEBAR COLUMN (297px width, sits right next to Budget & Financial card, perfectly aligning right edge with Name Card) -->
        <div class="shrink-0 flex flex-col w-[297px]" style="width: 297px; min-width: 297px; gap: 10.5px !important;">

          <!-- LEAD SCORE SUMMARY CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-2.5 cursor-pointer hover:border-emerald-500 transition" @click="openTimelineModal = true" title="Click to view score details">
            <div class="border-b border-gray-100 pb-2 dark:border-gray-800 flex items-center justify-between">
              <h3 style="font-size: 11px !important; font-weight: 600 !important;" class="uppercase tracking-wider text-gray-700 dark:text-gray-200">Lead Score</h3>
            </div>
            
            <div class="flex items-center gap-4 py-0.5">
              <!-- Score Ring -->
              <div class="relative flex items-center justify-center shrink-0" style="width: 44px; height: 44px;">
                <svg style="width: 44px; height: 44px;" class="transform -rotate-90">
                  <circle cx="22" cy="22" r="18" stroke="currentColor" stroke-width="3" class="text-gray-100 dark:text-gray-800" fill="transparent" />
                  <circle cx="22" cy="22" r="18" stroke="currentColor" stroke-width="3" class="text-emerald-500" fill="transparent"
                          stroke-dasharray="113.09"
                          stroke-dashoffset="{{ 113.09 - (113.09 * $leadScoreInfo['score']) / 100 }}"
                          stroke-linecap="round" />
                </svg>
                <span class="absolute font-bold text-gray-800 dark:text-white" style="font-size: 12px;">{{ $leadScoreInfo['score'] }}</span>
              </div>

              <!-- Text & Link (shifted slightly to the right) -->
              <div class="min-w-0 pl-1" style="display: flex; flex-direction: column; gap: 2px;">
                <span class="font-semibold text-emerald-600 dark:text-emerald-400 block" style="font-size: 11.5px; line-height: 1.1;">
                  High Potential
                </span>
                <p class="text-gray-500 dark:text-gray-400 font-normal" style="font-size: 9.5px; line-height: 1.3; color: #6b7280;">
                  {{ $leadScoreInfo['subtitle'] }}
                </p>
                <span class="font-semibold text-brand-500 hover:underline cursor-pointer" style="font-size: 9.5px; line-height: 1.2; margin-top: 1px; color: #4f46e5;">View Score Details</span>
              </div>
            </div>
          </div>

          <!-- 1. TASKS SUMMARY CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
              <h3 style="font-size: 11px !important; font-weight: 600 !important;" class="uppercase tracking-wider text-gray-700 dark:text-gray-200">Tasks ({{ $enquiry->tasks->count() }})</h3>
              <div class="flex items-center gap-2">
                <button type="button" @click="openCreateTaskModal = true" style="font-size: 11.5px !important; font-weight: 500 !important;" class="text-emerald-600 hover:underline cursor-pointer">+ New</button>
                <button type="button" @click="activeTab = 'tasks'" style="font-size: 11.5px !important; font-weight: 500 !important;" class="text-brand-500 hover:underline cursor-pointer">View All</button>
              </div>
            </div>

            @if($enquiry->tasks->isEmpty())
              <div class="text-center py-2.5">
                <p class="text-[10.5px] text-gray-400 font-normal">No tasks logged.</p>
                <button type="button" @click="openCreateTaskModal = true" style="font-size: 11.5px !important; font-weight: 500 !important;" class="text-brand-500 hover:underline mt-1 inline-block cursor-pointer">
                  + Add Task
                </button>
              </div>
            @else
              <div class="space-y-2">
                @foreach($enquiry->tasks->take(3) as $sideTask)
                  <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-2.5 text-xs space-y-1.5 dark:border-gray-800 dark:bg-gray-900/30">
                    <div class="flex items-start gap-2.5">
                      <form method="POST" action="{{ route('admin.tasks.toggle', $sideTask) }}" class="mt-0.5 shrink-0">
                        @csrf
                        <input type="checkbox" onchange="this.form.submit()" {{ $sideTask->status === 'completed' ? 'checked' : '' }} class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 cursor-pointer w-3.5 h-3.5">
                      </form>
                      <div class="min-w-0">
                        <span class="font-medium text-gray-700 dark:text-gray-300 block text-[10.5px] {{ $sideTask->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                          {{ $sideTask->title }}
                        </span>
                        <span class="text-[9.5px] text-gray-400 font-normal block mt-0.5">
                          Due: {{ $sideTask->due_date ? $sideTask->due_date->format('d M, Y h:i A') : 'No due date' }}
                        </span>
                      </div>
                    </div>
                    <div class="flex items-center justify-between pt-0.5">
                      <span class="text-[9px] font-normal text-gray-400 uppercase">Assigned: {{ $sideTask->assignedAgent?->name ?: 'Staff' }}</span>
                      @if($sideTask->status === 'completed')
                        <span class="inline-flex items-center rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400 px-1.5 py-0.5 text-[9px] font-semibold">Done</span>
                      @else
                        <span class="inline-flex items-center rounded-md bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 px-1.5 py-0.5 text-[9px] font-medium uppercase">{{ $sideTask->status }}</span>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

          <!-- 3. FOLLOW UPS SUMMARY CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
              <h3 style="font-size: 11px !important; font-weight: 600 !important;" class="uppercase tracking-wider text-gray-700 dark:text-gray-200">Follow Ups ({{ $enquiry->followUps->count() }})</h3>
              <div class="flex items-center gap-2">
                <button type="button" @click="openCreateFollowUpModal = true" style="font-size: 11.5px !important; font-weight: 500 !important;" class="text-emerald-600 hover:underline cursor-pointer">+ New</button>
                <button type="button" @click="activeTab = 'follow_ups'" style="font-size: 11.5px !important; font-weight: 500 !important;" class="text-brand-500 hover:underline cursor-pointer">View All</button>
              </div>
            </div>

            @if($enquiry->followUps->isEmpty())
              <div class="text-center py-2.5">
                <p class="text-[10.5px] text-gray-400 font-normal">No follow-ups logged.</p>
                <button type="button" @click="openCreateFollowUpModal = true" style="font-size: 11.5px !important; font-weight: 500 !important;" class="text-brand-500 hover:underline mt-1 inline-block cursor-pointer">
                  + Schedule Follow-up
                </button>
              </div>
            @else
              <div class="space-y-2">
                @foreach($enquiry->followUps->take(3) as $sideFollowUp)
                  <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-2.5 space-y-0.5 dark:border-gray-800 dark:bg-gray-900/30">
                    <div style="font-size: 10.5px !important;" class="flex items-center justify-between gap-1.5 font-medium">
                      <div class="flex items-center gap-1.5 text-blue-600 dark:text-blue-400 min-w-0">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span style="font-size: 10.5px !important;" class="text-gray-700 dark:text-gray-300 font-medium truncate">
                          {{ $sideFollowUp->outcome ? str_replace('_', ' ', ucfirst($sideFollowUp->outcome)) : 'Follow-up Call' }}
                        </span>
                      </div>
                      <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[9px] font-medium uppercase shrink-0 {{ $sideFollowUp->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400' }}">
                        {{ $sideFollowUp->status }}
                      </span>
                    </div>
                    <span style="font-size: 9.5px !important; color: #64748b !important;" class="block font-normal pl-5">
                      {{ $sideFollowUp->scheduled_at ? $sideFollowUp->scheduled_at->format('d M, Y h:i A') : 'No date set' }}
                    </span>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

          <!-- 4. DOCUMENTS SUMMARY CARD -->
          <div class="rounded-2xl border border-gray-200/80 bg-white p-3.5 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-800">
              <h3 style="font-size: 11px !important; font-weight: 600 !important;" class="uppercase tracking-wider text-gray-700 dark:text-gray-200">Documents ({{ $documents->count() }})</h3>
              <div class="flex items-center gap-2">
                <button type="button" @click="openUploadDocumentModal = true" style="font-size: 11.5px !important; font-weight: 500 !important;" class="text-emerald-600 hover:underline cursor-pointer">+ New</button>
                <button type="button" @click="activeTab = 'documents'" style="font-size: 11.5px !important; font-weight: 500 !important;" class="text-brand-500 hover:underline cursor-pointer">View All</button>
              </div>
            </div>

            @if($documents->isEmpty())
              <p class="text-[10.5px] text-gray-400 font-normal text-center py-2">No documents attached.</p>
            @else
              <div class="space-y-1.5">
                @foreach($documents->take(3) as $doc)
                  <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-gray-800/40">
                    <span class="font-medium text-[10.5px] text-gray-700 dark:text-gray-300 truncate max-w-[150px]">{{ $doc->file_name }}</span>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-brand-500 font-medium text-[10px] hover:underline">Download</a>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

        </div>

      </div>

    </div>

    <!-- MODALS -->

    <!-- UPLOAD DOCUMENT MODAL -->
    <div x-show="openUploadDocumentModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">

      <div @click.outside="openUploadDocumentModal = false"
           class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-900 p-6 shadow-xl border border-gray-100 dark:border-gray-800 space-y-4">
        
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
          <h3 class="text-base font-semibold text-gray-900 dark:text-white">Upload Customer Document</h3>
          <button type="button" @click="openUploadDocumentModal = false" class="text-gray-400 hover:text-gray-500 cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form method="POST" action="{{ route('admin.enquiries.upload-document', $enquiry->id) }}" enctype="multipart/form-data" class="space-y-4">
          @csrf
          
          <div>
            <label style="font-size: 9.5px !important; font-weight: 400 !important; color: #64748b !important;" class="block uppercase tracking-wider mb-1">
              Document Category <span class="text-rose-500">*</span>
            </label>
            <select name="category" required class="w-full rounded-xl border border-gray-200 bg-gray-50/50 p-2.5 text-xs text-gray-700 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-brand-500 focus:outline-none">
              <option value="General">General Document</option>
              <option value="PAN">PAN Card</option>
              <option value="Aadhaar">Aadhaar Card</option>
              <option value="Agreement">Agreement Copy</option>
              <option value="Receipt">Payment Receipt</option>
            </select>
          </div>

          <div>
            <label style="font-size: 9.5px !important; font-weight: 400 !important; color: #64748b !important;" class="block uppercase tracking-wider mb-1">
              Select Document File <span class="text-rose-500">*</span>
            </label>
            <input type="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 p-2 text-xs text-gray-700 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-brand-500 focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 dark:file:bg-brand-900/30 dark:file:text-brand-400">
            <span class="text-[10px] text-gray-400 mt-1 block">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 10MB)</span>
          </div>

          <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
            <button type="button" @click="openUploadDocumentModal = false" class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800 cursor-pointer">
              Cancel
            </button>
            <button type="submit" class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-semibold text-white hover:bg-brand-600 transition shadow-xs cursor-pointer">
              Upload Document
            </button>
          </div>
        </form>

      </div>
    </div>

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
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Budget Amount (₹)</label>
              <input type="number" step="any" name="budget" value="{{ old('budget', $enquiry->budget) }}" placeholder="e.g. 12000000 (1.2 Cr)" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
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

    <!-- DELETE LEAD CONFIRMATION MODAL -->
    <div x-show="openDeleteLeadModal" x-cloak
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
          <h3 class="text-base font-bold text-gray-900 dark:text-white">Delete Lead</h3>
          <button type="button" @click="openDeleteLeadModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
        </div>

        <p class="text-xs text-gray-600 dark:text-gray-300 mt-3 leading-relaxed">
          Are you sure you want to permanently delete lead <strong>{{ $enquiry->name }}</strong>? This action cannot be undone.
        </p>

        <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" class="mt-4 space-y-4">
          @csrf
          @method('DELETE')
          <div class="flex justify-end gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
            <button type="button" @click="openDeleteLeadModal = false" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
              Cancel
            </button>
            <button type="submit" class="rounded-xl bg-rose-600 hover:bg-rose-700 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
              Yes, Delete Lead
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- CREATE TASK MODAL -->
    <div x-show="openCreateTaskModal" x-cloak
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
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Create New Task</h3>
          <button type="button" @click="openCreateTaskModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
        </div>
        
        <form method="POST" action="{{ route('admin.tasks.store') }}" class="space-y-3 text-xs">
          @csrf
          <input type="hidden" name="enquiry_id" value="{{ $enquiry->id }}">

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Task Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" required placeholder="e.g. Share site visit report" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date &amp; Time</label>
              <input type="datetime-local" name="due_date" value="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
              <select name="priority" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                <option value="low">Low Priority</option>
                <option value="medium" selected>Medium Priority</option>
                <option value="high">High Priority</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
              <select name="status" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                <option value="pending" selected>Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>

            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned Agent</label>
              <select name="assigned_to" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                <option value="">Unassigned</option>
                @foreach($agents as $ag)
                  <option value="{{ $ag->id }}" {{ $enquiry->agent_id == $ag->id ? 'selected' : '' }}>
                    {{ $ag->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          @if($enquiry->followUps->isNotEmpty())
            <div>
              <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Connect with Follow-up (Optional)</label>
              <select name="follow_up_id" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                <option value="">General Task (No follow-up link)</option>
                @foreach($enquiry->followUps as $fl)
                  <option value="{{ $fl->id }}">
                    Follow-up on {{ $fl->scheduled_at ? $fl->scheduled_at->format('d M Y, h:i A') : 'N/A' }} ({{ ucfirst($fl->status) }})
                  </option>
                @endforeach
              </select>
            </div>
          @endif

          <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Task Details / Instructions</label>
            <textarea name="description" rows="2.5" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Enter notes..."></textarea>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
            <button type="button" @click="openCreateTaskModal = false" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
              Cancel
            </button>
            <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-1.5 font-semibold text-white">
              Create Task
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- EDIT LEAD DETAILS MODAL POPUP (Ultra-Compact 3-Column Grid Layout) -->
    <div x-show="openEditLeadModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex items-center justify-center p-2 sm:p-3"
         style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
      
      <div class="w-full rounded-2xl bg-white p-4 sm:p-5 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-h-[94vh] overflow-y-auto flex flex-col" style="max-width: 820px;">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 mb-2">
          <div class="flex items-center gap-2">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-50 text-brand-500 dark:bg-brand-500/10">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Edit Lead Information</h3>
              <span class="text-xs text-gray-400 font-normal">Update lead profile &amp; requirements</span>
            </div>
          </div>
          <button type="button" @click="openEditLeadModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
        </div>

        <!-- Modal Body Form (Side-by-Side 3-Column Grid Layout) -->
        <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" class="space-y-2">
          @csrf
          @method('PUT')

          <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 6px 14px;">
            <!-- Row 1 -->
            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Lead Name <span class="text-red-500">*</span></label>
              <input type="text" name="name" value="{{ old('name', $enquiry->name) }}" required placeholder="e.g. Ananya Sen" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Email Address</label>
              <input type="email" name="email" value="{{ old('email', $enquiry->email) }}" placeholder="ananya@example.com" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Phone Number</label>
              <input type="text" name="phone" value="{{ old('phone', $enquiry->phone) }}" placeholder="+91 99999 88888" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <!-- Row 2 -->
            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Lead Source</label>
              <input type="text" name="lead_source" value="{{ old('lead_source', $enquiry->lead_source) }}" placeholder="e.g. Website, Referral" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Lifecycle Status</label>
              <select name="status" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                @foreach(['draft', 'new', 'contacted', 'interested', 'follow_up', 'site_visit', 'negotiation', 'won', 'lost'] as $st)
                  <option value="{{ $st }}" {{ $enquiry->status === $st ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $st)) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Assigned Agent</label>
              <select name="agent_id" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                <option value="">Unassigned</option>
                @foreach($agents as $ag)
                  <option value="{{ $ag->id }}" {{ $enquiry->agent_id == $ag->id ? 'selected' : '' }}>
                    {{ $ag->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Row 3 -->
            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Location / City</label>
              <input type="text" name="location" value="{{ old('location', $enquiry->location) }}" placeholder="e.g. Gurgaon" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Property Type</label>
              <input type="text" name="preferred_type" value="{{ old('preferred_type', $enquiry->preferred_type) }}" placeholder="e.g. Apartment, Villa" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">BHK Configuration</label>
              <input type="text" name="bhk" value="{{ old('bhk', $enquiry->bhk) }}" placeholder="e.g. 3 BHK" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <!-- Row 4 -->
            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Carpet Area</label>
              <input type="text" name="carpet_area" value="{{ old('carpet_area', $enquiry->carpet_area) }}" placeholder="e.g. 1400 - 1800 sq.ft." class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Floor Preference</label>
              <input type="text" name="floor_preference" value="{{ old('floor_preference', $enquiry->floor_preference) }}" placeholder="e.g. High Floor" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Furnishing</label>
              <input type="text" name="furnishing" value="{{ old('furnishing', $enquiry->furnishing) }}" placeholder="e.g. Semi Furnished" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <!-- Row 5 -->
            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Budget Amount (₹)</label>
              <input type="number" step="any" name="budget" value="{{ old('budget', $enquiry->budget) }}" placeholder="e.g. 12000000 (1.2 Cr)" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Budget Type</label>
              <input type="text" name="budget_type" value="{{ old('budget_type', $enquiry->budget_type) }}" placeholder="e.g. Negotiable, Fixed" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Payment Mode</label>
              <input type="text" name="payment_mode" value="{{ old('payment_mode', $enquiry->payment_mode) }}" placeholder="e.g. Self, Loan + Self" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <!-- Row 6 -->
            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Loan Required</label>
              <select name="loan_required" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                <option value="0" {{ !$enquiry->loan_required ? 'selected' : '' }}>No</option>
                <option value="1" {{ $enquiry->loan_required ? 'selected' : '' }}>Yes</option>
              </select>
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Loan Amount (₹)</label>
              <input type="number" step="any" name="loan_amount" value="{{ old('loan_amount', $enquiry->loan_amount) }}" placeholder="e.g. 8000000 (80 L)" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div>
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Down Payment (₹)</label>
              <input type="number" step="any" name="down_payment" value="{{ old('down_payment', $enquiry->down_payment) }}" placeholder="e.g. 4000000 (40 L)" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <!-- Full 3-Column Span Fields -->
            <div style="grid-column: 1 / -1;">
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Preferred Areas / Other Preferences</label>
              <input type="text" name="other_preferences" value="{{ old('other_preferences', $enquiry->other_preferences) }}" placeholder="e.g. Bandra West, Worli, High Rise, Sea View" class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </div>

            <div style="grid-column: 1 / -1;">
              <label style="font-size: 9.5px !important; font-weight: 600;" class="block text-gray-700 dark:text-gray-300 mb-0.5">Lead Notes / Requirements Message</label>
              <textarea name="message" rows="1.5" style="min-height: 38px;" placeholder="Write lead requirement notes..." class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">{{ old('message', $enquiry->message) }}</textarea>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-gray-800 mt-1">
            <button type="button" @click="openEditLeadModal = false" class="rounded-lg border border-gray-200 bg-white px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 cursor-pointer">
              Cancel
            </button>
            <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-3.5 py-1 text-xs font-semibold text-white cursor-pointer transition">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

  </main>
@endsection
