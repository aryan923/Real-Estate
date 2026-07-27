@extends('admin.layouts.app')

@section('title', 'Dashboard - HomeSphere CRM')
@section('page', 'dashboard')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-8 min-h-screen bg-[#f8fafc] dark:bg-gray-950">
    <div class="p-4 md:p-6 mx-auto max-w-(--breakpoint-2xl) space-y-5 md:space-y-6">
      
      <!-- 1. TOP GREETING & DATE FILTER HEADER -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-1.5">
            Good morning, {{ explode(' ', $user->name)[0] }}! 👋
          </h1>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Here's what's happening with your business today.</p>
        </div>

        <div class="flex items-center gap-2">
          <button id="dashboard-date-range-btn" type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200/80 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 transition cursor-pointer">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span id="date-range-text">{{ $dateRangeLabel }}</span>
            <svg class="w-3 h-3 text-gray-400 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </button>
        </div>
      </div>

      <!-- 2. TOP 5 STAT CARDS (STRICTLY STACKED HORIZONTALLY IN ONE LINE) -->
      <div style="display: flex; flex-direction: row; gap: 10px; width: 100%; flex-wrap: nowrap;">
        
        <!-- Card 1: Total Leads -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between" style="flex: 1 1 0%; min-width: 0;">
          <div class="flex items-center gap-2.5">
            <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400 flex items-center justify-center shrink-0">
              <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
              <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 block truncate leading-none">Total Leads</span>
              <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tight block leading-tight mt-1">{{ number_format($totalLeads) }}</span>
            </div>
          </div>
          <div class="flex items-center justify-between pt-2 mt-1">
            <div class="flex items-center gap-1 min-w-0">
              <span class="inline-flex items-center text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">
                <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                18.7%
              </span>
              <span class="text-[9px] text-gray-400 font-normal truncate">vs last 7 days</span>
            </div>
            <div class="w-14 h-5 overflow-hidden shrink-0">
              <canvas id="sparklineLeadsCanvas" width="56" height="20"></canvas>
            </div>
          </div>
        </div>

        <!-- Card 2: Site Visits -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between" style="flex: 1 1 0%; min-width: 0;">
          <div class="flex items-center gap-2.5">
            <div class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center shrink-0">
              <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
              <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 block truncate leading-none">Site Visits</span>
              <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tight block leading-tight mt-1">{{ number_format($todayVisits) }}</span>
            </div>
          </div>
          <div class="flex items-center justify-between pt-2 mt-1">
            <div class="flex items-center gap-1 min-w-0">
              <span class="inline-flex items-center text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">
                <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                12.4%
              </span>
              <span class="text-[9px] text-gray-400 font-normal truncate">vs last 7 days</span>
            </div>
            <div class="w-14 h-5 overflow-hidden shrink-0">
              <canvas id="sparklineVisitsCanvas" width="56" height="20"></canvas>
            </div>
          </div>
        </div>

        <!-- Card 3: Deals in Pipeline -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between" style="flex: 1 1 0%; min-width: 0;">
          <div class="flex items-center gap-2.5">
            <div class="h-9 w-9 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 flex items-center justify-center shrink-0">
              <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
              <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 block truncate leading-none">Deals in Pipeline</span>
              <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tight block leading-tight mt-1">{{ number_format($dealsInPipeline) }}</span>
            </div>
          </div>
          <div class="flex items-center justify-between pt-2 mt-1">
            <div class="flex items-center gap-1 min-w-0">
              <span class="inline-flex items-center text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">
                <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                15.3%
              </span>
              <span class="text-[9px] text-gray-400 font-normal truncate">vs last 7 days</span>
            </div>
            <div class="w-14 h-5 overflow-hidden shrink-0">
              <canvas id="sparklineDealsCanvas" width="56" height="20"></canvas>
            </div>
          </div>
        </div>

        <!-- Card 4: Bookings (This Month) -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between" style="flex: 1 1 0%; min-width: 0;">
          <div class="flex items-center gap-2.5">
            <div class="h-9 w-9 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-400 flex items-center justify-center shrink-0">
              <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div class="min-w-0 flex-1">
              <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 block truncate leading-none">Bookings <span class="text-[9.5px] text-gray-400">(This Month)</span></span>
              <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tight block leading-tight mt-1">{{ number_format($monthlyBookings) }}</span>
            </div>
          </div>
          <div class="flex items-center justify-between pt-2 mt-1">
            <div class="flex items-center gap-1 min-w-0">
              <span class="inline-flex items-center text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">
                <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                21.6%
              </span>
              <span class="text-[9px] text-gray-400 font-normal truncate">vs last 7 days</span>
            </div>
            <div class="w-14 h-5 overflow-hidden shrink-0">
              <canvas id="sparklineBookingsCanvas" width="56" height="20"></canvas>
            </div>
          </div>
        </div>

        <!-- Card 5: Revenue (This Month) -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between" style="flex: 1 1 0%; min-width: 0;">
          <div class="flex items-center gap-2.5">
            <div class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center shrink-0">
              <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
              <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 block truncate leading-none">Revenue <span class="text-[9.5px] text-gray-400">(This Month)</span></span>
              <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tight block leading-tight mt-1">₹{{ number_format($revenueVal, 2) }} Cr</span>
            </div>
          </div>
          <div class="flex items-center justify-between pt-2 mt-1">
            <div class="flex items-center gap-1 min-w-0">
              <span class="inline-flex items-center text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 shrink-0">
                <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                26.8%
              </span>
              <span class="text-[9px] text-gray-400 font-normal truncate">vs last 7 days</span>
            </div>
            <div class="w-14 h-5 overflow-hidden shrink-0">
              <canvas id="sparklineRevenueCanvas" width="56" height="20"></canvas>
            </div>
          </div>
        </div>

      </div>

      <!-- 3. MIDDLE SECTION GRID (3 EQUAL COLUMNS IN ONE HORIZONTAL ROW) -->
      <div style="display: flex; flex-direction: row; gap: 12px; width: 100%; flex-wrap: nowrap;">

        <!-- Column 1: Recent Leads Card -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between space-y-3" style="flex: 1 1 0%; min-width: 0;">
          <div>
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 mb-2.5">
              <h2 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Recent Leads</h2>
              <a href="{{ route('admin.enquiries.index') }}" class="text-[11px] font-medium text-brand-500 hover:underline">View All</a>
            </div>

            <div class="space-y-2">
              @foreach($recentLeads as $lead)
                <div class="flex items-center justify-between text-xs py-0.5">
                  <div class="flex items-center gap-2 min-w-0">
                    <div class="h-6.5 w-6.5 rounded-full flex items-center justify-center font-bold text-[9.5px] shrink-0 {{ $lead['bg'] }}">
                      {{ $lead['initials'] }}
                    </div>
                    <div class="min-w-0">
                      <span class="font-semibold text-gray-900 dark:text-white block truncate text-[11px] leading-tight">{{ $lead['name'] }}</span>
                      <span class="text-[9.5px] text-gray-400 dark:text-gray-500 block truncate mt-0.5">{{ $lead['sub'] }}</span>
                    </div>
                  </div>
                  <div class="flex items-center gap-1.5 shrink-0 ml-1">
                    @php
                      $badgeStyle = match(strtolower($lead['status'])) {
                        'new' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400',
                        'contacted' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400',
                        'follow_up' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-400',
                        'qualified', 'interested' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400',
                        default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300'
                      };
                    @endphp
                    <span class="rounded-md px-1.5 py-0.5 text-[8.5px] font-medium capitalize {{ $badgeStyle }}">
                      {{ str_replace('_', ' ', $lead['status']) }}
                    </span>
                    <span class="text-[9px] text-gray-400 min-w-8 text-right">{{ $lead['time'] }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <div class="pt-2 border-t border-gray-100 dark:border-gray-800 text-center">
            <a href="{{ route('admin.enquiries.index') }}" class="text-[11px] font-semibold text-brand-500 hover:underline">View All Leads</a>
          </div>
        </div>

        <!-- Column 2: Deals Pipeline Funnel Horizontal Bar Chart -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between space-y-2" style="flex: 1 1 0%; min-width: 0;">
          <div>
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 mb-2">
              <h2 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Deals Pipeline</h2>
              <select onchange="updateDashboardPeriod(this.value)" class="rounded-md border border-gray-200 bg-gray-50 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-300 outline-none cursor-pointer">
                <option value="This Month" {{ $selectedPeriod === 'This Month' ? 'selected' : '' }}>This Month</option>
                <option value="This Quarter" {{ $selectedPeriod === 'This Quarter' ? 'selected' : '' }}>This Quarter</option>
                <option value="This Year" {{ $selectedPeriod === 'This Year' ? 'selected' : '' }}>This Year</option>
              </select>
            </div>

            <!-- Chart.js Pipeline Container -->
            <div class="w-full h-40">
              <canvas id="pipelineCanvas"></canvas>
            </div>
          </div>

          <div class="pt-2 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs">
            <div>
              <span class="text-[9px] text-gray-400 uppercase font-medium block">Conversion Rate</span>
              <span class="font-bold text-gray-900 dark:text-white text-xs">5.8%</span>
            </div>
            <div class="text-right">
              <span class="text-[9px] text-gray-400 uppercase font-medium block">Avg. Deal Value</span>
              <span class="font-bold text-gray-900 dark:text-white text-xs">₹87.5 L</span>
            </div>
          </div>
        </div>

        <!-- Column 3: Revenue Overview Area Chart -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between space-y-2" style="flex: 1 1 0%; min-width: 0;">
          <div>
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 mb-2">
              <h2 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Revenue Overview</h2>
              <select onchange="updateDashboardPeriod(this.value)" class="rounded-md border border-gray-200 bg-gray-50 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-300 outline-none cursor-pointer">
                <option value="This Month" {{ $selectedPeriod === 'This Month' ? 'selected' : '' }}>This Month</option>
                <option value="This Quarter" {{ $selectedPeriod === 'This Quarter' ? 'selected' : '' }}>This Quarter</option>
                <option value="This Year" {{ $selectedPeriod === 'This Year' ? 'selected' : '' }}>This Year</option>
              </select>
            </div>

            <div class="flex items-baseline gap-2 mb-1">
              <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">₹2.45 Cr</span>
              <span class="inline-flex items-center text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 px-1.5 py-0.5 rounded-md dark:bg-emerald-950/50">
                <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                26.8% <span class="text-[8.5px] text-gray-400 font-normal ml-0.5">vs last month</span>
              </span>
            </div>

            <!-- Chart.js Area Chart Container -->
            <div class="w-full h-36">
              <canvas id="revenueCanvas"></canvas>
            </div>
          </div>
        </div>

      </div>

      <!-- 4. BOTTOM SECTION GRID (3 EQUAL COLUMNS IN ONE HORIZONTAL ROW) -->
      <div style="display: flex; flex-direction: row; gap: 12px; width: 100%; flex-wrap: nowrap;">

        <!-- Column 1: Properties by Type Donut Card -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between space-y-3" style="flex: 1 1 0%; min-width: 0;">
          <div>
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 mb-2.5">
              <h2 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Properties by Type</h2>
            </div>

            <!-- Chart.js Donut Container -->
            <div class="w-full h-36 flex items-center justify-center">
              <canvas id="donutCanvas"></canvas>
            </div>
          </div>

          <div class="pt-2 border-t border-gray-100 dark:border-gray-800 text-center">
            <a href="{{ route('admin.properties') }}" class="text-[11px] font-semibold text-brand-500 hover:underline">View All Properties</a>
          </div>
        </div>

        <!-- Column 2: Top Performing Locations Card -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between space-y-3" style="flex: 1 1 0%; min-width: 0;">
          <div>
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 mb-2.5">
              <h2 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Top Performing Locations</h2>
              <select onchange="updateDashboardPeriod(this.value)" class="rounded-md border border-gray-200 bg-gray-50 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-300 outline-none cursor-pointer">
                <option value="This Month" {{ $selectedPeriod === 'This Month' ? 'selected' : '' }}>This Month</option>
                <option value="This Quarter" {{ $selectedPeriod === 'This Quarter' ? 'selected' : '' }}>This Quarter</option>
                <option value="This Year" {{ $selectedPeriod === 'This Year' ? 'selected' : '' }}>This Year</option>
              </select>
            </div>

            <div class="space-y-2 pt-0.5">
              @foreach($topLocations as $loc)
                <div class="space-y-0.5 text-xs">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 min-w-0">
                      <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                      <span class="font-medium text-gray-700 dark:text-gray-300 truncate text-[10.5px]">{{ $loc['name'] }}</span>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white shrink-0 ml-1 text-[10.5px]">{{ $loc['val'] }}</span>
                  </div>
                  <div class="w-full bg-gray-100 dark:bg-gray-800 h-1.5 rounded-full overflow-hidden">
                    <div class="h-1.5 rounded-full {{ $loc['color'] }}" style="width: {{ $loc['pct'] }}%;"></div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <div class="pt-2 border-t border-gray-100 dark:border-gray-800 text-center">
            <a href="#" class="text-[11px] font-semibold text-brand-500 hover:underline">View Full Report</a>
          </div>
        </div>

        <!-- Column 3: Upcoming Activities Card -->
        <div class="rounded-xl border border-gray-200/80 bg-white p-3.5 shadow-2xs dark:border-gray-800 dark:bg-gray-900 flex flex-col justify-between space-y-3" style="flex: 1 1 0%; min-width: 0;">
          <div>
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 mb-2.5">
              <h2 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Upcoming Activities</h2>
              <a href="{{ route('admin.enquiries.index') }}" class="text-[11px] font-medium text-brand-500 hover:underline">View Calendar</a>
            </div>

            <div class="space-y-2">
              @foreach($upcomingActivities as $act)
                <div class="flex items-center justify-between text-xs py-0.5">
                  <div class="flex items-center gap-2 min-w-0">
                    <div class="h-6.5 w-6.5 rounded-lg flex items-center justify-center shrink-0 {{ $act['color'] }}">
                      @if($act['type'] === 'visit')
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                      @elseif($act['type'] === 'call')
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                      @elseif($act['type'] === 'meeting')
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                      @else
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                      @endif
                    </div>
                    <div class="min-w-0">
                      <span class="font-semibold text-gray-900 dark:text-white block truncate text-[11px] leading-tight">{{ $act['title'] }}</span>
                      <span class="text-[9.5px] text-gray-400 dark:text-gray-500 block truncate mt-0.5">{{ $act['sub'] }}</span>
                    </div>
                  </div>
                  <div class="text-right shrink-0 ml-1">
                    <span class="block text-[9.5px] font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ explode(' ', $act['time'])[0] }}</span>
                    <span class="block text-[8.5px] text-gray-400 whitespace-nowrap mt-0.5">{{ implode(' ', array_slice(explode(' ', $act['time']), 1)) }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <div class="pt-2 border-t border-gray-100 dark:border-gray-800 text-center">
            <a href="{{ route('admin.enquiries.index') }}" class="text-[11px] font-semibold text-brand-500 hover:underline">View All Activities</a>
          </div>
        </div>

      </div>

      <!-- 5. BOTTOM PERFORMANCE GOAL BANNER -->
      <div class="rounded-xl border border-emerald-200/80 bg-emerald-50/70 p-3 px-4 dark:border-emerald-900/50 dark:bg-emerald-950/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <div class="h-8.5 w-8.5 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <h3 class="text-xs font-bold text-gray-900 dark:text-white">Great job! You've closed 28 deals this month.</h3>
            <p class="text-[10.5px] text-gray-500 dark:text-gray-400 mt-0.5">You are 40% ahead of your monthly target.</p>
          </div>
        </div>
        <button type="button" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1 text-xs font-semibold text-gray-700 shadow-2xs hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 transition cursor-pointer">
          View Goals
        </button>
      </div>

    </div>
  </main>

@push('scripts')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    function updateDashboardPeriod(periodVal) {
      const url = new URL(window.location.href);
      url.searchParams.set('period', periodVal);
      window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function () {
      // 0. Flatpickr Date Range Picker Integration
      const dateBtn = document.getElementById("dashboard-date-range-btn");
      if (typeof flatpickr !== 'undefined' && dateBtn) {
        let initialDateStr = "";

        const fp = flatpickr(dateBtn, {
          mode: "range",
          dateFormat: "Y-m-d",
          clickOpens: false,
          defaultDate: [
            @json($startDateParam ?: \Carbon\Carbon::now()->subDays(6)->format('Y-m-d')),
            @json($endDateParam ?: \Carbon\Carbon::now()->format('Y-m-d'))
          ],
          onOpen: function(selectedDates, dateStr) {
            initialDateStr = dateStr;
          },
          onChange: function(selectedDates, dateStr) {
            if (selectedDates.length === 2 && dateStr !== initialDateStr && dateStr.includes(" to ")) {
              const startStr = flatpickr.formatDate(selectedDates[0], "Y-m-d");
              const endStr = flatpickr.formatDate(selectedDates[1], "Y-m-d");
              const url = new URL(window.location.href);
              url.searchParams.set('start_date', startStr);
              url.searchParams.set('end_date', endStr);
              window.location.href = url.toString();
            }
          }
        });

        dateBtn.addEventListener("click", function(e) {
          e.stopPropagation();
          if (fp.isOpen) {
            fp.close();
          } else {
            fp.open();
          }
        });
      }
      if (typeof Chart === 'undefined') return;

      // 1. Helper function for Sparkline charts
      function renderSparkline(canvasId, dataPoints, strokeColor) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        new Chart(canvas.getContext('2d'), {
          type: 'line',
          data: {
            labels: ['', '', '', '', '', '', ''],
            datasets: [{
              data: dataPoints,
              borderColor: strokeColor,
              borderWidth: 1.8,
              tension: 0.35,
              pointRadius: 0,
              fill: false
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: { x: { display: false }, y: { display: false } }
          }
        });
      }

      renderSparkline('sparklineLeadsCanvas', @json($sparklineLeads), '#3b82f6');
      renderSparkline('sparklineVisitsCanvas', @json($sparklineVisits), '#10b981');
      renderSparkline('sparklineDealsCanvas', @json($sparklineDeals), '#f59e0b');
      renderSparkline('sparklineBookingsCanvas', @json($sparklineBookings), '#8b5cf6');
      renderSparkline('sparklineRevenueCanvas', @json($sparklineRevenue), '#10b981');

      // 2. Deals Pipeline Horizontal Bar Chart
      const pipelineCanvas = document.getElementById('pipelineCanvas');
      if (pipelineCanvas) {
        new Chart(pipelineCanvas.getContext('2d'), {
          type: 'bar',
          data: {
            labels: @json($funnelChart['labels']),
            datasets: [{
              data: @json($funnelChart['data']),
              backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#f43f5e', '#14b8a6'],
              borderRadius: 4,
              barThickness: 14
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { display: false },
              y: { ticks: { font: { size: 9.5, weight: '600' } }, grid: { display: false } }
            }
          }
        });
      }

      // 3. Revenue Overview Area Chart
      const revenueCanvas = document.getElementById('revenueCanvas');
      if (revenueCanvas) {
        new Chart(revenueCanvas.getContext('2d'), {
          type: 'line',
          data: {
            labels: @json($revenueChart['categories']),
            datasets: [{
              label: 'Revenue (₹ Cr)',
              data: @json($revenueChart['data']),
              borderColor: '#10b981',
              backgroundColor: 'rgba(16, 185, 129, 0.12)',
              fill: true,
              tension: 0.4,
              borderWidth: 2,
              pointBackgroundColor: '#10b981',
              pointRadius: 3
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { grid: { display: false }, ticks: { font: { size: 9 } } },
              y: { grid: { borderDash: [3, 3] }, ticks: { font: { size: 9 }, callback: v => v + ' Cr' } }
            }
          }
        });
      }

      // 4. Properties by Type Donut Chart
      const donutCanvas = document.getElementById('donutCanvas');
      if (donutCanvas) {
        new Chart(donutCanvas.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: @json($donutProperties['labels']),
            datasets: [{
              data: @json($donutProperties['series']),
              backgroundColor: ['#3b82f6', '#f59e0b', '#f97316', '#8b5cf6', '#10b981'],
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: 'right', labels: { font: { size: 9.5 } } }
            },
            cutout: '65%'
          }
        });
      }
    });
  </script>
@endpush
@endsection
