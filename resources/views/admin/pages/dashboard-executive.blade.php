@extends('admin.layouts.app')

@section('title', 'Executive Dashboard')
@section('page', 'dashboard')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      
      <!-- Greeting Header -->
      <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Welcome back, {{ $user->name }}!</h1>
        <p class="text-xs text-gray-500 mt-1">Sales Executive Dashboard portal. Focus on pending follow-ups and scheduled visits.</p>
      </div>

      <!-- KPIs cards -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <span class="text-xs text-gray-400 uppercase font-semibold">Assigned Leads</span>
          <div class="text-2xl font-black text-gray-900 dark:text-white mt-2">{{ $assignedLeads }}</div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-amber-50/10 dark:bg-amber-500/5">
          <span class="text-xs text-amber-600 dark:text-amber-400 uppercase font-semibold">Today's Follow-ups</span>
          <div class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-2">{{ $myTodayFollowups }}</div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] bg-blue-50/10 dark:bg-blue-500/5">
          <span class="text-xs text-blue-600 dark:text-blue-400 uppercase font-semibold">Upcoming Site Visits</span>
          <div class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-2">{{ $mySiteVisits }}</div>
        </div>
      </div>

      <!-- Main Panels -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Column 1: Today's Tasks & Upcoming Site Visits -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Scheduled Site Visits list -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Site Visit Arrangements</h2>
            @if($scheduledVisits->isEmpty())
              <p class="text-xs text-gray-500 text-center py-8">No scheduled site visit tours assigned to you.</p>
            @else
              <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-xs text-left">
                  <thead class="bg-gray-50 dark:bg-gray-900/40 text-gray-500">
                    <tr>
                      <th class="px-4 py-3 font-semibold uppercase">Client</th>
                      <th class="px-4 py-3 font-semibold uppercase">Project</th>
                      <th class="px-4 py-3 font-semibold uppercase">Scheduled Time</th>
                      <th class="px-4 py-3 font-semibold uppercase">Logistics Pickup</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($scheduledVisits as $visit)
                      <tr>
                        <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">
                          {{ $visit->enquiry?->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $visit->project?->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $visit->scheduled_at->format('d M, h:i A') }}</td>
                        <td class="px-4 py-3 text-gray-400">
                          @if($visit->pickup_required)
                            <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">Cab allocated</span>
                          @else
                            <span class="text-gray-400">Self transport</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>

          <!-- Hot Leads widget -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Hot Leads (High Lead Score)</h2>
            @if($hotLeads->isEmpty())
              <p class="text-xs text-gray-500 text-center py-6">No hot leads assigned yet.</p>
            @else
              <ul class="space-y-3">
                @foreach($hotLeads as $lead)
                  <li class="flex items-center justify-between border-b border-gray-50 dark:border-gray-800 pb-2 last:border-0 last:pb-0 text-xs">
                    <div>
                      <span class="font-bold text-gray-800 dark:text-white block">{{ $lead->name }}</span>
                      <span class="text-gray-400 block">{{ $lead->email }} | {{ $lead->phone }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                      <span class="inline-flex items-center rounded-md bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">
                        Score: {{ $lead->lead_score }}
                      </span>
                      <a href="{{ route('admin.enquiries.show', $lead->id) }}" class="text-brand-500 hover:text-brand-600 font-semibold">View Details</a>
                    </div>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>

        </div>

        <!-- Column 2: Today's scheduled calls list -->
        <div class="space-y-6">
          
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">Today's Call Tasks</h2>
            @if($todayTasks->isEmpty())
              <p class="text-xs text-gray-500 text-center py-6">No pending calls scheduled for today.</p>
            @else
              <ul class="space-y-4">
                @foreach($todayTasks as $t)
                  <li class="border-b border-gray-50 dark:border-gray-800 pb-3 last:border-0 last:pb-0 text-xs space-y-1.5">
                    <div class="flex justify-between items-center">
                      <span class="font-bold text-gray-800 dark:text-white">{{ $t->scheduled_at->format('h:i A') }}</span>
                      <span class="rounded bg-amber-50 px-1.5 py-0.5 font-bold uppercase text-[9px] text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Pending</span>
                    </div>
                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">Call {{ $t->enquiry?->name }}</div>
                    <p class="text-gray-500">{{ $t->enquiry?->phone }}</p>
                    <a href="{{ route('admin.follow-ups.index') }}" class="text-brand-500 font-bold hover:underline block pt-1">Log Call Outcome</a>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>

        </div>

      </div>

    </div>
  </main>
@endsection
