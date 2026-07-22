@extends('admin.layouts.app')

@section('title', 'Analytics Reports')
@section('page', 'reports')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-6">
        
        <!-- Filter Bar Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 flex-1 max-w-4xl">
              <!-- Project Filter -->
              <div>
                <label for="project_id" class="mb-2 block text-xs font-semibold text-gray-800 dark:text-white">Project</label>
                <select name="project_id" id="project_id" class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  <option value="">All Projects</option>
                  @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ $p->id == $selectedProjectId ? 'selected' : '' }}>{{ $p->name }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Start Date -->
              <div>
                <label for="start_date" class="mb-2 block text-xs font-semibold text-gray-800 dark:text-white">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
              </div>

              <!-- End Date -->
              <div>
                <label for="end_date" class="mb-2 block text-xs font-semibold text-gray-800 dark:text-white">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="w-full h-10 rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
              </div>
            </div>

            <div class="flex items-center gap-3">
              <button type="submit" class="inline-flex h-10 items-center justify-center rounded-lg bg-brand-500 hover:bg-brand-600 px-5 text-xs font-bold text-white transition">
                Apply Filters
              </button>
              <a href="{{ route('admin.reports.index') }}" class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-200 bg-white px-4 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
                Reset
              </a>
            </div>
          </form>
        </div>

        <!-- 6 Main Reports Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          
          <!-- Report 1: Lead Report -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Lead Report</h2>
                <button onclick="triggerExport('lead')" class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">Export</button>
              </div>
              <div class="flex items-baseline gap-2 mb-4">
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $totalLeads }}</span>
                <span class="text-xs text-gray-500 font-semibold">Total Inquiries</span>
              </div>
              <div class="space-y-2.5">
                <div>
                  <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>New Leads</span>
                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ $leadsByStatus['new'] ?? 0 }}</span>
                  </div>
                  <div class="w-full bg-gray-100 h-1.5 rounded-full dark:bg-gray-800">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $totalLeads > 0 ? (($leadsByStatus['new'] ?? 0) / $totalLeads) * 100 : 0 }}%"></div>
                  </div>
                </div>
                <div>
                  <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Contacted</span>
                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ $leadsByStatus['contacted'] ?? 0 }}</span>
                  </div>
                  <div class="w-full bg-gray-100 h-1.5 rounded-full dark:bg-gray-800">
                    <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $totalLeads > 0 ? (($leadsByStatus['contacted'] ?? 0) / $totalLeads) * 100 : 0 }}%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Report 2: Sales Report -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Sales Report</h2>
                <button onclick="triggerExport('sales')" class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">Export</button>
              </div>
              <div class="flex items-baseline gap-2 mb-4">
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">₹{{ number_format($totalSalesValue / 10000000, 2) }} Cr</span>
                <span class="text-xs text-gray-500 font-semibold">Bookings ({{ $totalSalesCount }} Units)</span>
              </div>
              <dl class="grid grid-cols-2 gap-4 text-xs">
                <div class="border border-gray-100 rounded-xl p-3 dark:border-gray-800">
                  <span class="text-gray-400 block font-medium">Avg Deal Value</span>
                  <span class="font-bold text-gray-900 dark:text-white mt-1 block">₹{{ number_format($avgSalesValue, 2) }}</span>
                </div>
                <div class="border border-gray-100 rounded-xl p-3 dark:border-gray-800">
                  <span class="text-gray-400 block font-medium">Agreement Value</span>
                  <span class="font-bold text-gray-900 dark:text-white mt-1 block">₹{{ number_format($totalSalesValue, 2) }}</span>
                </div>
              </dl>
            </div>
          </div>

          <!-- Report 3: Revenue Report -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Revenue Report</h2>
                <button onclick="triggerExport('revenue')" class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">Export</button>
              </div>
              <div class="flex items-baseline gap-2 mb-4">
                <span class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">₹{{ number_format($totalRevenueCollected / 10000000, 2) }} Cr</span>
                <span class="text-xs text-gray-500 font-semibold">Collected</span>
              </div>
              <div class="space-y-3">
                <div class="flex justify-between text-xs font-semibold">
                  <span class="text-gray-400">Outstanding Balance:</span>
                  <span class="text-amber-600">₹{{ number_format($totalRevenuePending, 2) }}</span>
                </div>
                <div class="relative w-full bg-gray-100 rounded-full h-3 dark:bg-gray-800 overflow-hidden">
                  @php
                    $totalRev = $totalRevenueCollected + $totalRevenuePending;
                    $pct = $totalRev > 0 ? ($totalRevenueCollected / $totalRev) * 100 : 0;
                  @endphp
                  <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <div class="flex justify-between text-[10px] text-gray-400">
                  <span>{{ round($pct, 1) }}% Collected</span>
                  <span>{{ round(100 - $pct, 1) }}% Outstanding</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Report 4: Conversion Funnel -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Conversion Funnel</h2>
                <button onclick="triggerExport('conversion')" class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">Export</button>
              </div>
              <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-gray-50 dark:border-gray-800 pb-2">
                  <span class="text-xs text-gray-500">Leads → Site Visit Rate</span>
                  <span class="text-xs font-bold text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded text-blue-700 dark:text-blue-400">
                    {{ round($siteVisitConversionRate, 1) }}%
                  </span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-xs text-gray-500">Leads → Booking Sale Rate</span>
                  <span class="text-xs font-bold text-gray-900 dark:text-white bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded text-emerald-700 dark:text-emerald-400">
                    {{ round($bookingConversionRate, 1) }}%
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Report 5: Inventory Allocation -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Inventory Status</h2>
                <button onclick="triggerExport('inventory')" class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">Export</button>
              </div>
              <div class="flex items-baseline gap-2 mb-4">
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $totalInventoryCount }}</span>
                <span class="text-xs text-gray-500 font-semibold">Total Registered Units</span>
              </div>
              <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                <div class="flex items-center justify-between border-b border-gray-50 dark:border-gray-800 pb-1">
                  <span class="flex items-center gap-1"><span class="h-2.5 w-2.5 bg-emerald-500 rounded-sm"></span> Available</span>
                  <span class="font-bold text-gray-700 dark:text-gray-300">{{ $inventoryByStatus['available'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-gray-50 dark:border-gray-800 pb-1">
                  <span class="flex items-center gap-1"><span class="h-2.5 w-2.5 bg-rose-500 rounded-sm"></span> Sold</span>
                  <span class="font-bold text-gray-700 dark:text-gray-300">{{ $inventoryByStatus['sold'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-gray-50 dark:border-gray-800 pb-1">
                  <span class="flex items-center gap-1"><span class="h-2.5 w-2.5 bg-amber-500 rounded-sm"></span> Reserved</span>
                  <span class="font-bold text-gray-700 dark:text-gray-300">{{ $inventoryByStatus['reserved'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-gray-50 dark:border-gray-800 pb-1">
                  <span class="flex items-center gap-1"><span class="h-2.5 w-2.5 bg-gray-900 rounded-sm"></span> Blocked</span>
                  <span class="font-bold text-gray-700 dark:text-gray-300">{{ $inventoryByStatus['blocked'] ?? 0 }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Report 6: Site Visit Tour Performance -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Site Visits Report</h2>
                <button onclick="triggerExport('visits')" class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">Export</button>
              </div>
              <div class="flex items-baseline gap-2 mb-4">
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $totalVisits }}</span>
                <span class="text-xs text-gray-500 font-semibold">Tours Scheduled</span>
              </div>
              <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                  <span class="text-gray-500">Visited (Completed)</span>
                  <span class="font-bold text-gray-800 dark:text-white">{{ $visitsByStatus['visited'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-500">Scheduled (Pending)</span>
                  <span class="font-bold text-gray-800 dark:text-white">{{ $visitsByStatus['scheduled'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-500">Cancelled / No-shows</span>
                  <span class="font-bold text-gray-800 dark:text-white">{{ ($visitsByStatus['cancelled'] ?? 0) + ($visitsByStatus['not_visited'] ?? 0) }}</span>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    function triggerExport(reportName) {
      fetch(`/admin/reports/${reportName}/export`)
        .then(res => res.json())
        .then(data => {
          alert(data.message);
        });
    }
  </script>
@endpush
