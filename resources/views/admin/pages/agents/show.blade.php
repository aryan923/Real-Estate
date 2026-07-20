@extends('admin.layouts.app')

@section('title', 'Agent Details')
@section('page', 'agents')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6 space-y-6">
      
      <!-- Top Action Navigation -->
      <div class="flex items-center justify-between">
        <a href="{{ route('admin.agents') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-500 hover:text-brand-600 transition">
          &larr; Back to Agents
        </a>
      </div>

      <!-- Main Agent Profile Header Card -->
      <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div class="flex items-center gap-4">
            <!-- Profile Photo -->
            <div class="relative flex-shrink-0">
              @if($user->profile_pic)
                <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($user->profile_pic) }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-full border border-gray-200 object-cover shadow-xs dark:border-gray-700" />
              @else
                <div class="flex h-16 w-16 items-center justify-center rounded-full border border-dashed border-gray-300 bg-gray-50 text-xl font-bold text-gray-400 dark:border-gray-700 dark:bg-gray-800">
                  {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
              @endif
            </div>

            <!-- Profile Info Text -->
            <div>
              <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white leading-tight">{{ $user->name }}</h1>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                  {{ ucfirst($user->status) }}
                </span>
                @if($user->agentMeta?->is_featured)
                  <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                    Featured
                  </span>
                @endif
              </div>
              <p class="text-sm font-medium text-brand-500 mt-1">{{ $user->agentMeta?->title ?? 'Real Estate Agent' }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $user->agentMeta?->company ?? 'PropDesk Real Estate' }}</p>
            </div>
          </div>

          <!-- Edit Profile Action Button -->
          <div>
            <a href="{{ route('admin.agents.edit', $user) }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600">
              Edit Profile
            </a>
          </div>
        </div>
      </div>

      <!-- Split Layout Columns -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left Column: Contact and Details Card -->
        <div class="md:col-span-1 space-y-6">
          <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact &amp; Details</h2>
            
            <div class="space-y-4">
              <!-- Email -->
              <div class="border-b border-gray-100 pb-3 dark:border-gray-850">
                <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Email Address</span>
                <a href="mailto:{{ $user->email }}" class="text-sm font-medium text-brand-500 hover:underline block mt-1 break-all">{{ $user->email }}</a>
              </div>

              <!-- Phone -->
              <div class="border-b border-gray-100 pb-3 dark:border-gray-850">
                <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Phone Number</span>
                <a href="tel:{{ $user->phone }}" class="text-sm font-medium text-brand-500 hover:underline block mt-1">{{ $user->phone }}</a>
              </div>

              <!-- Location -->
              @if($user->agentMeta?->address)
                <div class="border-b border-gray-100 pb-3 dark:border-gray-850">
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Office Location</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">{{ $user->agentMeta->address }}</span>
                </div>
              @endif

              <!-- Languages -->
              @if($user->agentMeta?->languages)
                <div class="border-b border-gray-100 pb-3 dark:border-gray-850">
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Languages</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">{{ $user->agentMeta->languages }}</span>
                </div>
              @endif

              <!-- Experience -->
              @if($user->agentMeta?->experience_years)
                <div class="border-b border-gray-100 pb-3 dark:border-gray-850">
                  <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400">Experience</span>
                  <span class="text-sm font-medium text-gray-800 dark:text-gray-200 block mt-1">{{ $user->agentMeta->experience_years }}</span>
                </div>
              @endif
            </div>

            <!-- Specialties -->
            @if($user->agentMeta?->specialties)
              <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-850">
                <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Specialties &amp; Areas</span>
                <div class="flex flex-wrap gap-1.5">
                  @foreach(explode(',', $user->agentMeta->specialties) as $tag)
                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-750 dark:bg-gray-800 dark:text-gray-300">
                      {{ trim($tag) }}
                    </span>
                  @endforeach
                </div>
              </div>
            @endif
          </section>
        </div>

        <!-- Right Column: Assigned Leads Table -->
        <div class="md:col-span-2 space-y-6">
          <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Assigned Leads</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leads and enquiries assigned to {{ $user->name }}.</p>
              </div>
              <span class="rounded-lg bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700 dark:bg-brand-500/10 dark:text-brand-300">
                Total leads: {{ $enquiries->total() }}
              </span>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
              <table class="min-w-full table-auto">
                <thead>
                  <tr class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/40">
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Lead</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Property</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Received</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                  @forelse ($enquiries as $lead)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                      <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ $lead->name }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $lead->email }}</div>
                        @if($lead->phone)
                          <div class="text-xs text-gray-500 mt-0.5">{{ $lead->phone }}</div>
                        @endif
                      </td>
                      <td class="px-5 py-4">
                        @if($lead->property)
                          <a href="{{ route('admin.properties.edit', $lead->property) }}" class="text-sm font-semibold text-brand-500 hover:underline block max-w-[200px] truncate">
                            {{ $lead->property->title }}
                          </a>
                          <span class="inline-flex rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-semibold text-gray-600 mt-1 dark:bg-gray-850 dark:text-gray-400">
                            {{ ucfirst($lead->property->property_purpose) }}
                          </span>
                        @else
                          <span class="text-sm text-gray-400 italic">General Inquiry</span>
                        @endif
                      </td>
                      <td class="px-5 py-4">
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
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-800' }}">
                          {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                        </span>
                      </td>
                      <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400">
                        {{ $lead->created_at->format('M d, Y') }}
                      </td>
                      <td class="px-5 py-4">
                        <a href="{{ route('admin.enquiries.show', $lead) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5 transition">View</a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        No leads assigned to this agent yet.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            @if($enquiries->hasPages())
              <div class="mt-6">
                {{ $enquiries->links() }}
              </div>
            @endif
          </section>
        </div>

      </div>

    </div>
  </main>
@endsection
