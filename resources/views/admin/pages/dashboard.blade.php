@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page', 'dashboard')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      
      <!-- User Greeting -->
      <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Welcome back, {{ $user->name }}!</h1>
        <p class="text-xs text-gray-500 mt-1">Here is a summary of your real estate business overview today.</p>
      </div>

      <!-- KPI Summary Cards -->
      <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6 mb-6">
        <!-- Total Leads -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <span class="text-xs text-gray-400 uppercase font-semibold">Total Leads</span>
          <div class="text-2xl font-black text-gray-900 dark:text-white mt-2">{{ $totalLeads }}</div>
        </div>

        <!-- Today Followups -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <span class="text-xs text-gray-400 uppercase font-semibold">Today's Follow-ups</span>
          <div class="text-2xl font-black text-brand-600 dark:text-brand-400 mt-2">{{ $todayFollowups }}</div>
        </div>

        <!-- Site Visits -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <span class="text-xs text-gray-400 uppercase font-semibold">Site Visits</span>
          <div class="text-2xl font-black text-gray-900 dark:text-white mt-2">{{ $todayVisits }}</div>
        </div>

        <!-- Bookings -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <span class="text-xs text-gray-400 uppercase font-semibold">Bookings</span>
          <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">{{ $totalBookings }}</div>
        </div>

        <!-- Revenue Collection -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-emerald-50/10 dark:bg-emerald-500/5">
          <span class="text-xs text-emerald-600 dark:text-emerald-400 uppercase font-semibold">Revenue</span>
          <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-2">₹{{ number_format($revenue / 10000000, 2) }} Cr</div>
        </div>

        <!-- Pending Payments -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-amber-50/10 dark:bg-amber-500/5">
          <span class="text-xs text-amber-600 dark:text-amber-400 uppercase font-semibold">Pending</span>
          <div class="text-xl font-black text-amber-600 dark:text-amber-400 mt-2">₹{{ number_format($pendingPayments / 100000, 0) }} Lakhs</div>
        </div>
      </div>

      <!-- Main Panels -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Column 1: Sales Charts & Leads breakdown -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Monthly Sales Chart placeholder -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Monthly Sales Progression</h2>
            @if($monthlySales->isEmpty())
              <p class="text-xs text-gray-500 text-center py-10">No sales recorded in the last 6 months.</p>
            @else
              <div class="space-y-4">
                @foreach($monthlySales as $sale)
                  <div>
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                      <span>{{ $sale->month }}</span>
                      <span class="font-bold text-gray-800 dark:text-white">₹{{ number_format($sale->total, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full dark:bg-gray-800">
                      @php
                        $maxTotal = $monthlySales->max('total') ?: 1;
                        $pct = ($sale->total / $maxTotal) * 100;
                      @endphp
                      <div class="bg-brand-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

          <!-- Leads by Source Breakdown -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Lead Source Distribution</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
              @foreach($leadsBySource as $src)
                <div class="border border-gray-100 rounded-xl p-4 text-center dark:border-gray-800">
                  <span class="text-xs text-gray-400 block font-medium">{{ $src['source'] }}</span>
                  <span class="text-xl font-bold text-gray-900 dark:text-white mt-1 block">{{ $src['count'] }}</span>
                </div>
              @endforeach
            </div>
          </div>

        </div>

        <!-- Column 2: Tasks and Recent Activities widgets -->
        <div class="space-y-6">
          
          <!-- Today's Tasks Widget -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Today's Schedule Tasks</h2>
            @if($tasks->isEmpty())
              <p class="text-xs text-gray-500 text-center py-6">No scheduled follow-up tasks today.</p>
            @else
              <ul class="space-y-3">
                @foreach($tasks as $t)
                  <li class="flex items-center justify-between text-xs">
                    <div>
                      <span class="font-bold text-gray-800 dark:text-white block">{{ $t->scheduled_at->format('h:i A') }} - Call {{ $t->enquiry?->name }}</span>
                      <span class="text-gray-400">{{ $t->enquiry?->phone }}</span>
                    </div>
                    <span class="rounded bg-amber-50 px-2 py-0.5 font-bold uppercase text-[9px] text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Call Pending</span>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>

          <!-- Recent Activity Widget -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Recent Activities Log</h2>
            <ul class="space-y-4">
              @foreach($activities as $act)
                <li class="flex gap-3 text-xs">
                  <div class="h-6 w-6 rounded-full flex items-center justify-center flex-shrink-0 bg-{{ $act['color'] }}-50 text-{{ $act['color'] }}-600 dark:bg-{{ $act['color'] }}-500/10 dark:text-{{ $act['color'] }}-400">
                    ★
                  </div>
                  <div>
                    <span class="font-bold text-gray-800 dark:text-white block">{{ $act['title'] }}</span>
                    <p class="text-gray-500 mt-0.5">{{ $act['desc'] }}</p>
                    <span class="text-[9px] text-gray-400 mt-1 block">{{ $act['time'] }}</span>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>

        </div>

      </div>

    </div>
  </main>
@endsection
