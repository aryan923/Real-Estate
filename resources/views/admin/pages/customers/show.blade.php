@extends('admin.layouts.app')

@section('title', $customer->name)
@section('page', 'customers')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10" x-data="{ activeTab: 'overview' }">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        
        <!-- Customer Profile Header Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
              <div class="h-16 w-16 overflow-hidden rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-2xl dark:bg-brand-500/10 dark:text-brand-400">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
              </div>
              <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                  {{ $customer->occupation ?: 'Occupation not specified' }}
                </p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Profile
              </a>
              <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">Back</a>
            </div>
          </div>
        </div>

        <!-- Detail Tabs Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-800">
          <nav class="-mb-px flex gap-6">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
              Overview
            </button>
            <button @click="activeTab = 'bookings'" :class="activeTab === 'bookings' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
              Bookings
            </button>
            <button @click="activeTab = 'payments'" :class="activeTab === 'payments' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
              Payments
            </button>
            <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
              Documents
            </button>
          </nav>
        </div>

        <!-- Tab Contents -->
        <div>
          <!-- Tab 1: Overview -->
          <div x-show="activeTab === 'overview'" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
              <!-- Personal Details -->
              <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Email Address</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $customer->email }}</dd>
                  </div>
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Phone Number</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $customer->phone ?: 'N/A' }}</dd>
                  </div>
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">PAN Number</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $customer->pan_number ?: 'N/A' }}</dd>
                  </div>
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Aadhaar Number</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $customer->aadhaar_number ?: 'N/A' }}</dd>
                  </div>
                </dl>
              </div>

              <!-- Budget & Interests -->
              <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">Preferences</h3>
                <dl class="space-y-4">
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Budget Range</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $customer->budget_range ?: 'Not specified' }}</dd>
                  </div>
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider mb-1">Interested Projects</dt>
                    <dd class="flex flex-wrap gap-2">
                      @forelse($interestedProjects as $proj)
                        <span class="inline-flex items-center rounded-md bg-brand-50 px-2 py-1 text-xs font-semibold text-brand-700 dark:bg-brand-500/10 dark:text-brand-400">
                          {{ $proj->name }}
                        </span>
                      @empty
                        <span class="text-sm text-gray-500">None selected</span>
                      @endforelse
                    </dd>
                  </div>
                </dl>
              </div>

            </div>

            <!-- Family Members List -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">Family Details</h3>
              @if(empty($customer->family_members))
                <p class="text-sm text-gray-500">No family details registered.</p>
              @else
                <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
                  <table class="min-w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                      <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Relationship</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                      @foreach($customer->family_members as $member)
                        <tr>
                          <td class="px-5 py-3 text-sm text-gray-800 dark:text-white">{{ $member['name'] ?? 'N/A' }}</td>
                          <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $member['relation'] ?? 'N/A' }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>

          </div>

          <!-- Tab 2: Bookings -->
          <div x-show="activeTab === 'bookings'" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Booking Records</h2>
            @if($bookings->isEmpty())
              <p class="text-sm text-gray-500">No bookings found for this customer.</p>
            @else
              <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
                <table class="min-w-full table-auto">
                  <thead class="bg-gray-50 dark:bg-gray-900/40">
                    <tr>
                      <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Project</th>
                      <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Unit Number</th>
                      <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Booking Date</th>
                      <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Total Price</th>
                      <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($bookings as $booking)
                      <tr>
                        <td class="px-5 py-3 text-sm text-gray-800 dark:text-white">{{ $booking->project?->name ?: 'N/A' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-800 dark:text-white">{{ $booking->property?->unit_number ?: 'N/A' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $booking->booking_date ? $booking->booking_date->format('d M Y') : 'N/A' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-800 dark:text-white">₹{{ number_format($booking->total_price, 2) }}</td>
                        <td class="px-5 py-3 text-sm">
                          <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wide {{ $booking->booking_status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $booking->booking_status }}
                          </span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>

          <!-- Tab 3: Payments -->
          <div x-show="activeTab === 'payments'" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Payment Receipts</h2>
            <div class="text-center py-6 text-gray-500">
              <p class="text-sm">No payment records found.</p>
            </div>
          </div>

          <!-- Tab 4: Documents -->
          <div x-show="activeTab === 'documents'" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Documents Attachments</h2>
            @if($documents->isEmpty())
              <p class="text-sm text-gray-500">No documents uploaded.</p>
            @else
              <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($documents as $doc)
                  <li class="py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-800 dark:text-white">{{ $doc->name }}</span>
                    <a href="{{ asset($doc->path) }}" target="_blank" class="text-brand-500 hover:text-brand-600 text-xs font-semibold">Download</a>
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
