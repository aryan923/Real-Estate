@extends('admin.layouts.app')

@section('title', 'Leads Dashboard')
@section('page', 'enquiries')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
  <style>
    /* Ultra-Clean Benchmark Table Styling (Image 2 Parity) */
    #enquiries-table_wrapper .dataTables_filter { display: none; }
    #enquiries-table_wrapper .dataTables_length { display: none; }
    #enquiries-table { border-collapse: collapse !important; width: 100% !important; table-layout: fixed !important; }
    #enquiries-table th { padding: 10px 10px !important; border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important; font-size: 11px !important; font-weight: 700 !important; color: #64748b !important; white-space: nowrap !important; text-transform: uppercase !important; }
    #enquiries-table td { padding: 10px 10px !important; border-bottom: 1px solid rgba(241, 245, 249, 0.8) !important; vertical-align: middle !important; overflow: visible !important; position: relative !important; }
    #enquiries-table tr { position: relative !important; }
    .dark #enquiries-table th { border-bottom-color: rgba(51, 65, 85, 0.5) !important; color: #94a3b8 !important; }
    .dark #enquiries-table td { border-bottom-color: rgba(30, 41, 59, 0.5) !important; }
    .dataTables_paginate { margin-top: 14px !important; font-size: 12px !important; display: flex !important; justify-content: flex-end !important; align-items: center !important; gap: 3px !important; }
    .paginate_button { border-radius: 6px !important; padding: 4px 10px !important; font-weight: 600 !important; cursor: pointer !important; }
  </style>
@endpush

@section('content')
  @include('admin.partials.admin-header')

  <main class="w-full max-w-full min-w-0 overflow-x-hidden p-3 sm:p-4 md:p-6 space-y-5">
    
    <!-- Top Header Breadcrumb & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 min-w-0">
      <div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">Leads</h1>
        <p class="text-[11px] text-gray-400">Dashboard &gt; Leads</p>
      </div>

      <div class="flex items-center gap-2">
        <!-- Import Leads Button -->
        <button type="button" id="btn-open-import" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-2xs transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800 cursor-pointer">
          <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
          </svg>
          Import Leads
        </button>

        <!-- + Add Lead Button -->
        <button type="button" id="btn-open-add-lead" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3.5 py-1.5 text-xs font-bold text-white shadow-2xs transition hover:bg-brand-600 cursor-pointer">
          <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          + Add Lead
        </button>
      </div>
    </div>

    <!-- 1. KPI Summary Cards (Full 100% Width Proportional Row) -->
    <div class="flex flex-wrap lg:flex-nowrap items-center gap-3 w-full mb-3">
      <!-- Total Leads -->
      <div class="flex-1 min-w-[170px] inline-flex items-center gap-2.5 rounded-xl border border-gray-200/80 bg-white px-3.5 py-2 shadow-2xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="h-8 w-8 shrink-0 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center dark:bg-blue-500/10 dark:text-blue-400">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
        <div class="min-w-0 flex-1">
          <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block whitespace-nowrap">Total Leads</span>
          <div class="flex items-baseline gap-1.5 mt-0.5">
            <span class="text-base font-extrabold text-gray-900 dark:text-white leading-none">{{ number_format($stats['total'] ?? 0) }}</span>
            <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400">&uarr; 18.7%</span>
          </div>
        </div>
      </div>

      <!-- New Leads -->
      <div class="flex-1 min-w-[170px] inline-flex items-center gap-2.5 rounded-xl border border-gray-200/80 bg-white px-3.5 py-2 shadow-2xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="h-8 w-8 shrink-0 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center dark:bg-emerald-500/10 dark:text-emerald-400">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        </div>
        <div class="min-w-0 flex-1">
          <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block whitespace-nowrap">New Leads</span>
          <div class="flex items-baseline gap-1.5 mt-0.5">
            <span class="text-base font-extrabold text-gray-900 dark:text-white leading-none">{{ number_format($stats['new'] ?? 0) }}</span>
            <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400">&uarr; 12.4%</span>
          </div>
        </div>
      </div>

      <!-- Qualified Leads -->
      <div class="flex-1 min-w-[170px] inline-flex items-center gap-2.5 rounded-xl border border-gray-200/80 bg-white px-3.5 py-2 shadow-2xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="h-8 w-8 shrink-0 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center dark:bg-amber-500/10 dark:text-amber-400">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
        </div>
        <div class="min-w-0 flex-1">
          <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block whitespace-nowrap">Qualified</span>
          <div class="flex items-baseline gap-1.5 mt-0.5">
            <span class="text-base font-extrabold text-gray-900 dark:text-white leading-none">{{ number_format($stats['qualified'] ?? 0) }}</span>
            <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400">&uarr; 15.3%</span>
          </div>
        </div>
      </div>

      <!-- Converted Leads -->
      <div class="flex-1 min-w-[170px] inline-flex items-center gap-2.5 rounded-xl border border-gray-200/80 bg-white px-3.5 py-2 shadow-2xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="h-8 w-8 shrink-0 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center dark:bg-purple-500/10 dark:text-purple-400">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="min-w-0 flex-1">
          <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block whitespace-nowrap">Converted</span>
          <div class="flex items-baseline gap-1.5 mt-0.5">
            <span class="text-base font-extrabold text-gray-900 dark:text-white leading-none">{{ number_format($stats['converted'] ?? 0) }}</span>
            <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400">&uarr; 21.6%</span>
          </div>
        </div>
      </div>

      <!-- Conversion Rate -->
      <div class="flex-1 min-w-[170px] inline-flex items-center gap-2.5 rounded-xl border border-gray-200/80 bg-white px-3.5 py-2 shadow-2xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="h-8 w-8 shrink-0 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center dark:bg-cyan-500/10 dark:text-cyan-400">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        </div>
        <div class="min-w-0 flex-1">
          <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block whitespace-nowrap">Conv. Rate</span>
          <div class="flex items-baseline gap-1.5 mt-0.5">
            <span class="text-base font-extrabold text-gray-900 dark:text-white leading-none">{{ $stats['conversion_rate'] ?? 0 }}%</span>
            <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400">&uarr; 3.2%</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. Main Lead Card Section -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-800 dark:bg-white/[0.03] space-y-4 min-w-0">
      
      <!-- Pipeline Status Tabs (Image 2 Parity) -->
      <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
        <nav class="flex items-center gap-1.5 text-xs whitespace-nowrap overflow-x-auto scrollbar-none" id="status-tabs-nav">
          <button type="button" data-status="all" class="tab-btn active px-3 py-1.5 rounded-lg transition cursor-pointer bg-brand-500 text-white font-bold">
            All Leads <span class="ml-1 text-[10px] opacity-80">({{ $statusCounts['all'] ?? 0 }})</span>
          </button>
          <button type="button" data-status="new" class="tab-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
            New <span class="ml-1 text-[10px] text-gray-400">({{ $statusCounts['new'] ?? 0 }})</span>
          </button>
          <button type="button" data-status="contacted" class="tab-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
            Contacted <span class="ml-1 text-[10px] text-gray-400">({{ $statusCounts['contacted'] ?? 0 }})</span>
          </button>
          <button type="button" data-status="interested" class="tab-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
            Interested <span class="ml-1 text-[10px] text-gray-400">({{ $statusCounts['interested'] ?? 0 }})</span>
          </button>
          <button type="button" data-status="site_visit" class="tab-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
            Site Visit <span class="ml-1 text-[10px] text-gray-400">({{ $statusCounts['site_visit'] ?? 0 }})</span>
          </button>
          <button type="button" data-status="negotiation" class="tab-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
            Negotiation <span class="ml-1 text-[10px] text-gray-400">({{ $statusCounts['negotiation'] ?? 0 }})</span>
          </button>
          <button type="button" data-status="won" class="tab-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
            Closed Won <span class="ml-1 text-[10px] text-gray-400">({{ $statusCounts['won'] ?? 0 }})</span>
          </button>
          <button type="button" data-status="lost" class="tab-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
            Closed Lost <span class="ml-1 text-[10px] text-gray-400">({{ $statusCounts['lost'] ?? 0 }})</span>
          </button>
        </nav>
      </div>

      <!-- 3. Modern Filter Bar (Reduced Search Width & Increased Filter Spacing) -->
      <div class="flex flex-wrap lg:flex-nowrap items-center justify-between gap-4 min-w-0 pt-1 pb-2 mb-3">
        
        <!-- Live AJAX Search Input (Compact 220px Width + Guaranteed pl-11 Left Padding) -->
        <div class="relative w-[220px] shrink-0">
          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 z-10">
            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input type="text" id="ajax-search-input" placeholder="Search by name, email, phone..." class="w-full h-10 rounded-xl border border-gray-200 bg-gray-50/50 pl-11 pr-3 text-xs text-gray-800 transition focus:border-brand-500 focus:bg-white focus:outline-none dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 shadow-2xs">
        </div>

        <!-- Inline Filter Dropdowns (Considerable Distance / Spacing gap-4) -->
        <div class="flex flex-wrap items-center gap-4 sm:gap-5 min-w-0">
          <select id="filter-lead-source" class="h-10 rounded-xl border border-gray-200 bg-gray-50/50 px-3.5 text-xs sm:text-sm font-medium text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 shadow-2xs transition hover:border-gray-300 dark:hover:border-gray-700">
            <option value="">Lead Source</option>
            @foreach($sources as $src)
              <option value="{{ $src }}">{{ $src }}</option>
            @endforeach
          </select>

          <select id="filter-agent" class="h-10 rounded-xl border border-gray-200 bg-gray-50/50 px-3.5 text-xs sm:text-sm font-medium text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 shadow-2xs transition hover:border-gray-300 dark:hover:border-gray-700">
            <option value="">Assigned To</option>
            @foreach($agents as $agt)
              <option value="{{ $agt->id }}">{{ $agt->name }}</option>
            @endforeach
          </select>

          <select id="filter-location" class="h-10 rounded-xl border border-gray-200 bg-gray-50/50 px-3.5 text-xs sm:text-sm font-medium text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 shadow-2xs transition hover:border-gray-300 dark:hover:border-gray-700">
            <option value="">Location</option>
            @foreach($locations as $loc)
              <option value="{{ $loc }}">{{ $loc }}</option>
            @endforeach
          </select>

          <button type="button" id="btn-clear-filters" class="h-10 px-3 text-xs sm:text-sm font-bold text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 cursor-pointer transition">
            Clear Filters
          </button>
        </div>
      </div>

      <!-- Bulk Actions Bar -->
      <div id="bulk-actions-bar" class="hidden items-center justify-between rounded-lg bg-brand-50 p-2.5 dark:bg-brand-500/10 border border-brand-200 dark:border-brand-500/30 mb-3">
        <span class="text-xs font-semibold text-brand-700 dark:text-brand-300" id="bulk-selected-count">0 leads selected</span>
        <button type="button" id="btn-bulk-delete" class="rounded-md bg-rose-600 px-3 py-1 text-xs font-bold text-white transition hover:bg-rose-700 cursor-pointer">
          Delete Selected
        </button>
      </div>

      <!-- 4. Non-Cutoff 100% Fit Table Container -->
      <div class="w-full max-w-full rounded-xl border border-gray-100 dark:border-gray-800 pb-12 mt-2">
        <table id="enquiries-table" class="w-full text-left border-collapse table-fixed">
          <thead>
            <tr class="bg-gray-50 dark:bg-gray-900/60">
              <th class="w-[3%] text-center">
                <input type="checkbox" id="select-all-checkbox" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900">
              </th>
              <th class="w-[21%]">Lead Name</th>
              <th class="w-[19%]">Contact Info</th>
              <th class="w-[19%]">Requirement</th>
              <th class="w-[9%]">Source</th>
              <th class="w-[9%]">Status</th>
              <th class="w-[10%]">Assigned To</th>
              <th class="w-[6%]">Last Contact</th>
              <th class="w-[4%] text-right">Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Import CSV Modal Popup (Generous Vertical Spacing & Breathable Layout) -->
  <div id="modal-import-csv" class="fixed inset-0 hidden flex items-center justify-center p-4" style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
    <div class="w-full rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 flex flex-col" style="max-width: 480px;">
      
      <!-- Modal Header -->
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3.5 mb-4">
        <div class="flex items-center gap-2.5">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-50 text-brand-500 dark:bg-brand-500/10">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
          </div>
          <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Import Leads via CSV</h3>
            <span class="text-[11px] text-gray-400 font-normal">Batch upload lead records into system</span>
          </div>
        </div>
        <button type="button" id="btn-close-import" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
      </div>

      <!-- Modal Form -->
      <form id="form-import-csv" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="space-y-1.5">
          <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
            Upload a CSV file containing lead data. Ensure your file includes the required headers:
          </p>
          <div class="rounded-lg bg-gray-50 p-2.5 text-[11px] font-semibold text-gray-700 dark:bg-gray-800/60 dark:text-gray-300 border border-gray-100 dark:border-gray-800">
            Name, Email, Phone, Message, Source, Status, Location
          </div>
        </div>

        <div class="border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl p-6 text-center hover:border-brand-500 transition bg-gray-50/40 dark:bg-gray-900/50 my-3">
          <input type="file" name="csv_file" id="csv_file" accept=".csv, .txt" class="w-full text-xs text-gray-500 file:mr-3.5 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 cursor-pointer" required>
        </div>

        <!-- Footer Buttons -->
        <div class="flex justify-end gap-2.5 pt-3.5 border-t border-gray-100 dark:border-gray-800 mt-3">
          <button type="button" id="btn-cancel-import" class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 cursor-pointer">
            Cancel
          </button>
          <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4.5 py-2 text-xs font-semibold text-white cursor-pointer transition">
            Upload & Import
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Add New Lead Modal Popup (Compact 640px 2-Column Grid Layout with Generous Column Gap) -->
  <div id="modal-add-lead" class="fixed inset-0 hidden flex items-center justify-center p-4" style="z-index: 999999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);">
    <div class="w-full rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-800 max-h-[90vh] overflow-y-auto flex flex-col" style="max-width: 640px;">
      
      <!-- Modal Header -->
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
        <div class="flex items-center gap-2">
          <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-50 text-brand-500 dark:bg-brand-500/10">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
          </div>
          <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Add New Lead</h3>
            <span class="text-[11px] text-gray-400 font-normal">Create lead profile & requirements</span>
          </div>
        </div>
        <button type="button" id="btn-close-add-lead" class="text-gray-400 hover:text-gray-600 text-lg font-semibold cursor-pointer">&times;</button>
      </div>

      <!-- Modal Body Form (Side-by-Side 2-Column Grid with 24px Gap) -->
      <form id="form-add-lead" class="space-y-3">
        @csrf
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
          <!-- 1. Full Name -->
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" required placeholder="e.g. Ananya Sen" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
          </div>

          <!-- 2. Email Address -->
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address <span class="text-red-500">*</span></label>
            <input type="email" name="email" required placeholder="ananya@example.com" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
          </div>

          <!-- 3. Phone Number -->
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
            <input type="text" name="phone" placeholder="+91 99999 88888" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
          </div>

          <!-- 4. Lead Source -->
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Lead Source</label>
            <select name="lead_source" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              @foreach($sources as $src)
                <option value="{{ $src }}">{{ $src }}</option>
              @endforeach
            </select>
          </div>

          <!-- 5. Lifecycle Status -->
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Lifecycle Status</label>
            <select name="status" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              <option value="new">New</option>
              <option value="contacted">Contacted</option>
              <option value="interested">Interested</option>
              <option value="site_visit">Site Visit</option>
              <option value="negotiation">Negotiation</option>
              <option value="won">Closed Won</option>
              <option value="lost">Closed Lost</option>
            </select>
          </div>

          <!-- 6. Assigned Agent -->
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned Agent</label>
            <select name="agent_id" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
              <option value="">Auto Assign (Round Robin)</option>
              @foreach($agents as $agt)
                <option value="{{ $agt->id }}">{{ $agt->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- 7. Location -->
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Location / City</label>
            <input type="text" name="location" placeholder="e.g. Gurgaon" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
          </div>

          <!-- 8. Budget Specification -->
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Budget Specification</label>
            <input type="text" name="budget" placeholder="e.g. ₹1.2Cr" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
          </div>

          <!-- 9. Requirement / Notes (Full 2-Column Span) -->
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Requirement / Notes</label>
            <textarea name="message" rows="2" placeholder="Write lead requirement notes..." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white"></textarea>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800 mt-1">
          <button type="button" id="btn-cancel-add-lead" class="rounded-lg border border-gray-200 bg-white px-3.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 cursor-pointer">
            Cancel
          </button>
          <button type="submit" class="rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-1.5 text-xs font-semibold text-white cursor-pointer transition">
            Save Lead
          </button>
        </div>
      </form>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (!window.jQuery || !window.jQuery.fn.DataTable) {
        return;
      }

      const $ = window.jQuery;
      let activeStatus = 'all';

      // Elements
      const searchInput = $('#ajax-search-input');
      const sourceFilter = $('#filter-lead-source');
      const agentFilter = $('#filter-agent');
      const locationFilter = $('#filter-location');
      const clearBtn = $('#btn-clear-filters');

      // Colors array for initial bubbles
      const bgColors = [
        'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300',
        'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300',
        'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300',
        'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300',
        'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-300'
      ];

      // Helper function to escape HTML
      function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }

      // Initialize DataTables with pure Frontend View Formatters
      const table = $('#enquiries-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.enquiries.data') }}',
          data: function (d) {
            d.status = activeStatus;
            d.search_query = searchInput.val();
            d.lead_source = sourceFilter.val();
            d.agent_id = agentFilter.val();
            d.location = locationFilter.val();
          }
        },
        columns: [
          // 0. Checkbox Column
          {
            data: 'id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data) {
              return '<input type="checkbox" class="lead-select-checkbox rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900" value="' + data + '">';
            }
          },
          // 1. Lead Column (Name + Micro Single-Line Subtitle)
          {
            data: 'name',
            render: function (data, type, row) {
              const name = escapeHtml(row.name);
              const initials = escapeHtml(row.lead_initials || 'L');
              const avatarBg = bgColors[row.id % bgColors.length];

              let msg = row.message || row.property_title || 'Inquiry Registered';
              let phrase = msg.replace(/\s+/g, ' ').trim();
              if (phrase.length > 22) {
                phrase = phrase.substring(0, 22) + '...';
              }

              return '<div class="flex items-center gap-2 min-w-0 max-w-[180px]">' +
                '<div class="h-7 w-7 shrink-0 flex items-center justify-center rounded-full font-bold text-[10px] ' + avatarBg + '">' + initials + '</div>' +
                '<div class="min-w-0 flex-1 overflow-hidden" style="line-height: 1.2;">' +
                  '<a href="' + row.show_url + '" class="font-bold text-gray-900 hover:text-brand-600 dark:text-white dark:hover:text-brand-400 block truncate" style="font-size: 12px !important; font-weight: 700 !important; text-decoration: none;">' + name + '</a>' +
                  '<span style="font-size: 10px !important; font-weight: 400 !important; color: #94a3b8 !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; display: block !important; margin-top: 1px !important; max-w: 130px !important;">' + escapeHtml(phrase) + '</span>' +
                '</div>' +
              '</div>';
            }
          },
          // 2. Contact Info Column (Phone at 12px matching Lead Name)
          {
            data: 'phone',
            render: function (data, type, row) {
              const phone = row.phone ? escapeHtml(row.phone) : 'N/A';
              const email = row.email ? escapeHtml(row.email) : '';
              return '<div class="text-xs leading-tight space-y-0.5 truncate max-w-[145px] pr-2">' +
                '<div class="font-bold text-gray-900 dark:text-white text-xs">' + phone + '</div>' +
                '<div class="text-gray-400 dark:text-gray-500 truncate text-[10px] font-normal">' + email + '</div>' +
              '</div>';
            }
          },
          // 3. Requirement Column (Title at 12px matching Lead Name)
          {
            data: 'property_title',
            orderable: false,
            render: function (data, type, row) {
              const prop = escapeHtml(row.property_title || 'Property Lead');
              const loc = row.location || '';
              const budget = row.budget || '';

              let line2 = [];
              if (loc) line2.push(loc);
              if (budget) line2.push('Budget ' + budget);
              const line2Str = escapeHtml(line2.join(', '));

              return '<div class="text-xs leading-tight max-w-[145px] overflow-hidden pr-2">' +
                '<div class="font-bold text-gray-900 dark:text-white truncate text-xs">' + prop + '</div>' +
                (line2Str ? '<div class="text-[10px] text-gray-400 dark:text-gray-500 font-normal truncate mt-0.5">' + line2Str + '</div>' : '') +
              '</div>';
            }
          },
          // 4. Source Column (Soft Gray Borderless Pill at 12px matching Lead Name)
          {
            data: 'lead_source',
            render: function (data, type, row) {
              const src = escapeHtml(row.lead_source || 'Website');
              return '<span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border-none whitespace-nowrap">' + src + '</span>';
            }
          },
          // 5. Status Column (Soft Pastel Borderless Pill Badges at 12px matching Lead Name)
          {
            data: 'status',
            render: function (data, type, row) {
              const status = row.status || 'new';
              let label = status.replace(/_/g, ' ');
              label = label.charAt(0).toUpperCase() + label.slice(1);

              let bg = '#f3f4f6', color = '#374151';
              if (status === 'won') { bg = '#e6f4ea'; color = '#137333'; }
              else if (status === 'lost') { bg = '#fce8e6'; color = '#c5221f'; }
              else if (status === 'new') { bg = '#e8f0fe'; color = '#1a73e8'; }
              else if (status === 'contacted') { bg = '#fef3c7'; color = '#92400e'; }
              else if (status === 'interested' || status === 'qualified') { bg = '#e6f4ea'; color = '#137333'; }
              else if (status === 'site_visit') { bg = '#e0f2fe'; color = '#0369a1'; }
              else if (status === 'negotiation') { bg = '#f3e8ff'; color = '#7e22ce'; }

              return '<span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold tracking-tight whitespace-nowrap" style="background-color: ' + bg + ' !important; color: ' + color + ' !important; border: none !important;">' + escapeHtml(label) + '</span>';
            }
          },
          // 6. Assigned Agent Column (Name at 12px matching Lead Name)
          {
            data: 'agent_name',
            orderable: false,
            render: function (data, type, row) {
              const agentName = escapeHtml(row.agent_name || 'Unassigned');
              const agentInitials = escapeHtml(row.agent_initials || 'U');

              if (row.agent_name && row.agent_name !== 'Unassigned') {
                return '<div class="flex items-center gap-1.5 whitespace-nowrap">' +
                  '<div class="h-5.5 w-5.5 shrink-0 flex items-center justify-center rounded-full bg-brand-500 text-white font-bold text-[10px]">' + agentInitials + '</div>' +
                  '<span class="text-xs font-bold text-gray-900 dark:text-white truncate max-w-[100px]">' + agentName + '</span>' +
                '</div>';
              }
              return '<span class="text-xs text-gray-400 italic font-normal">Unassigned</span>';
            }
          },
          // 7. Last Contact Column (Time at 12px matching Lead Name)
          {
            data: 'relative_time',
            render: function (data) {
              return '<span class="text-xs text-gray-700 dark:text-gray-300 font-semibold whitespace-nowrap">' + escapeHtml(data) + '</span>';
            }
          },
          // 8. Actions Column (Phone, Mail, 3-dots Dropdown)
          {
            data: 'id',
            orderable: false,
            searchable: false,
            className: 'text-right',
            render: function (data, type, row) {
              const phone = row.phone ? escapeHtml(row.phone) : '';
              const email = row.email ? escapeHtml(row.email) : '';

              const callBtn = phone
                ? '<a href="tel:' + phone + '" class="p-1 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition rounded" title="Call ' + phone + '">' +
                    '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>' +
                  '</a>'
                : '';

              const mailBtn = email
                ? '<a href="mailto:' + email + '" class="p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition rounded" title="Email ' + email + '">' +
                    '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>' +
                  '</a>'
                : '';

              const dropdownMenu = '<div class="relative inline-block text-left" x-data="{ open: false }">' +
                '<button type="button" @click.stop="open = !open" @click.outside="open = false" class="p-1 text-gray-400 hover:text-gray-700 dark:hover:text-white transition rounded cursor-pointer" title="More Options">' +
                  '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>' +
                '</button>' +
                '<div x-show="open" x-transition class="absolute right-0 top-full mt-1 z-50 w-36 origin-top-right rounded-xl bg-white p-1.5 shadow-xl ring-1 ring-black/10 dark:bg-gray-900 dark:ring-white/10" style="display: none;">' +
                  '<a href="' + row.show_url + '" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-lg dark:text-gray-200 dark:hover:bg-gray-800">' +
                    '<svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg> View Details' +
                  '</a>' +
                  '<a href="' + row.edit_url + '" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-lg dark:text-gray-200 dark:hover:bg-gray-800">' +
                    '<svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg> Edit Lead' +
                  '</a>' +
                  '<form method="POST" action="' + row.destroy_url + '" data-confirm="Delete this lead?">' +
                    '<input type="hidden" name="_token" value="' + row.csrf_token + '">' +
                    '<input type="hidden" name="_method" value="DELETE">' +
                    '<button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 rounded-lg dark:hover:bg-red-500/10 cursor-pointer">' +
                      '<svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg> Delete Lead' +
                    '</button>' +
                  '</form>' +
                '</div>' +
              '</div>';

              return '<div class="flex items-center justify-end gap-1 whitespace-nowrap">' + callBtn + mailBtn + dropdownMenu + '</div>';
            }
          }
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[7, 'desc']],
        drawCallback: function () {
          if (window.Alpine && window.Alpine.initTree) {
            window.Alpine.initTree(document.getElementById('enquiries-table'));
          }
        },
        language: {
          paginate: {
            previous: '&lsaquo; Prev',
            next: 'Next &rsaquo;'
          }
        }
      });

      // 1. Debounced Live Search (300ms)
      let searchTimeout = null;
      searchInput.on('keyup input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
          table.ajax.reload();
        }, 300);
      });

      // 2. Dropdown Filters
      sourceFilter.on('change', function () { table.ajax.reload(); });
      agentFilter.on('change', function () { table.ajax.reload(); });
      locationFilter.on('change', function () { table.ajax.reload(); });

      // 3. Clear Filters
      clearBtn.on('click', function () {
        searchInput.val('');
        sourceFilter.val('');
        agentFilter.val('');
        locationFilter.val('');
        activeStatus = 'all';
        $('.tab-btn').removeClass('bg-brand-500 text-white font-bold').addClass('text-gray-600 hover:bg-gray-100 dark:text-gray-400');
        $('.tab-btn[data-status="all"]').addClass('bg-brand-500 text-white font-bold').removeClass('text-gray-600');
        table.ajax.reload();
      });

      // 4. Horizontal Pipeline Tabs
      $('#status-tabs-nav').on('click', '.tab-btn', function () {
        $('.tab-btn').removeClass('bg-brand-500 text-white font-bold').addClass('text-gray-600 hover:bg-gray-100 dark:text-gray-400');
        $(this).addClass('bg-brand-500 text-white font-bold').removeClass('text-gray-600');
        activeStatus = $(this).data('status');
        table.ajax.reload();
      });

      // 5. Select All Checkbox & Bulk Actions
      $('#select-all-checkbox').on('change', function () {
        $('.lead-select-checkbox').prop('checked', this.checked);
        updateBulkBar();
      });

      $(document).on('change', '.lead-select-checkbox', function () {
        updateBulkBar();
      });

      function updateBulkBar() {
        const checked = $('.lead-select-checkbox:checked');
        if (checked.length > 0) {
          $('#bulk-actions-bar').removeClass('hidden').addClass('flex');
          $('#bulk-selected-count').text(checked.length + ' lead(s) selected');
        } else {
          $('#bulk-actions-bar').addClass('hidden').removeClass('flex');
        }
      }

      // 6. Bulk Delete Handler
      $('#btn-bulk-delete').on('click', function () {
        const ids = $('.lead-select-checkbox:checked').map(function () { return $(this).val(); }).get();
        if (ids.length === 0) return;

        if (confirm('Are you sure you want to delete ' + ids.length + ' selected lead(s)?')) {
          $.ajax({
            url: '{{ route('admin.enquiries.bulk-action') }}',
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              ids: ids,
              action: 'delete'
            },
            success: function (res) {
              $('#select-all-checkbox').prop('checked', false);
              updateBulkBar();
              table.ajax.reload();
            }
          });
        }
      });

      // 7. Import CSV Modal Handlers
      $('#btn-open-import').on('click', function () {
        $('#modal-import-csv').removeClass('hidden');
      });
      $('#btn-close-import, #btn-cancel-import').on('click', function () {
        $('#modal-import-csv').addClass('hidden');
      });

      $('#form-import-csv').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
          url: '{{ route('admin.enquiries.import') }}',
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function (res) {
            $('#modal-import-csv').addClass('hidden');
            $('#form-import-csv')[0].reset();
            table.ajax.reload();
            alert(res.message);
          },
          error: function () {
            alert('Import failed. Please check your CSV format.');
          }
        });
      });

      // 8. Add Lead Modal Handlers
      $('#btn-open-add-lead').on('click', function () {
        $('#modal-add-lead').removeClass('hidden');
      });
      $('#btn-close-add-lead, #btn-cancel-add-lead').on('click', function () {
        $('#modal-add-lead').addClass('hidden');
      });

      $('#form-add-lead').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
          url: '{{ route('admin.enquiries.store') }}',
          type: 'POST',
          data: formData,
          success: function (res) {
            $('#modal-add-lead').addClass('hidden');
            $('#form-add-lead')[0].reset();
            table.ajax.reload();
            alert('Lead created successfully!');
          },
          error: function (xhr) {
            let msg = 'Failed to create lead.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            alert(msg);
          }
        });
      });
    });
  </script>
@endpush
