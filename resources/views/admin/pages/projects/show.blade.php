@extends('admin.layouts.app')

@section('title', $project->name)
@section('page', 'projects')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10" x-data="{ activeTab: 'overview', activeTower: '{{ $towers->first() ?: '' }}' }">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        
        <!-- Project Header Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
                @switch($project->status)
                  @case('completed')
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">Completed</span>
                    @break
                  @case('under_construction')
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400">Under Construction</span>
                    @break
                  @case('planning')
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400">Planning</span>
                    @break
                  @default
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-500/10 dark:text-gray-400">Cancelled</span>
                @endswitch
              </div>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <svg class="inline-block h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $project->location ?: 'Location not specified' }}
              </p>
            </div>
            <div class="flex items-center gap-3">
              <a href="{{ route('admin.projects.edit', $project) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Details
              </a>
              <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">Back</a>
            </div>
          </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-5 md:gap-6">
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Units</span>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->total_units }}</span>
              <span class="text-xs text-gray-500">planned</span>
            </div>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Available</span>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $availableCount }}</span>
              <span class="text-xs text-gray-500">active</span>
            </div>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Sold</span>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $soldCount }}</span>
              <span class="text-xs text-gray-500">units</span>
            </div>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Reserved</span>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $reservedCount }}</span>
              <span class="text-xs text-gray-500">units</span>
            </div>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Blocked</span>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $blockedCount }}</span>
              <span class="text-xs text-gray-500">units</span>
            </div>
          </div>
        </div>

        <!-- Detail Tabs Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-800">
          <nav class="-mb-px flex gap-6">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
              Overview
            </button>
            <button @click="activeTab = 'inventory'" :class="activeTab === 'inventory' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
              Inventory (Unit Grid)
            </button>
            <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
              Documents
            </button>
            <button @click="activeTab = 'analytics'" :class="activeTab === 'analytics' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
              Sales Analytics
            </button>
          </nav>
        </div>

        <!-- Tab Contents -->
        <div>
          <!-- Tab 1: Overview -->
          <div x-show="activeTab === 'overview'" class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">About the Project</h2>
              <div class="prose max-w-none text-gray-700 dark:prose-invert dark:text-gray-300">
                {!! nl2br(e($project->description ?: 'No project description added yet.')) !!}
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">Details</h3>
                <dl class="grid grid-cols-2 gap-x-4 gap-y-4">
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Project ID</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">#PROJ-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}</dd>
                  </div>
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Location</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $project->location ?: 'N/A' }}</dd>
                  </div>
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Total Planned Units</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $project->total_units }}</dd>
                  </div>
                  <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Registered Units</dt>
                    <dd class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-1">{{ $unitsCount }}</dd>
                  </div>
                </dl>
              </div>

              <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-center items-center">
                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4 self-start">Sales Progression</h3>
                <div class="relative w-full bg-gray-100 rounded-full h-4 dark:bg-gray-800 overflow-hidden mb-2">
                  <div class="bg-brand-500 h-4 rounded-full transition-all duration-500" style="width: {{ $unitsCount > 0 ? ($soldCount / $unitsCount) * 100 : 0 }}%"></div>
                </div>
                <div class="flex justify-between w-full text-xs text-gray-500">
                  <span>{{ $soldCount }} Sold</span>
                  <span>{{ $unitsCount > 0 ? round(($soldCount / $unitsCount) * 100, 1) : 0 }}% Sold</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab 2: Inventory Grid -->
          <div x-show="activeTab === 'inventory'" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
              <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Inventory Grid</h2>
                <p class="text-xs text-gray-500">Filter by tower to visualize mapping status.</p>
              </div>
              <div class="flex flex-wrap items-center gap-3">
                <!-- Legend -->
                <div class="flex flex-wrap items-center gap-3 text-xs">
                  <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-emerald-500"></span> Available</span>
                  <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-rose-500"></span> Sold</span>
                  <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-amber-500"></span> Reserved</span>
                  <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-gray-900"></span> Blocked</span>
                </div>
                
                @if($towers->isNotEmpty())
                  <div class="flex items-center gap-2">
                    <label for="tower-select" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tower:</label>
                    <select id="tower-select" x-model="activeTower" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                      @foreach($towers as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                      @endforeach
                    </select>
                  </div>
                @endif
              </div>
            </div>

            @if($properties->isEmpty())
              <div class="text-center py-10">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">No properties added</h3>
                <p class="mt-1 text-sm text-gray-500">Properties/Units belong to projects. Add properties and assign them to this project.</p>
              </div>
            @else
              @foreach($towers as $tower)
                <div x-show="activeTower === '{{ $tower }}'" class="space-y-4">
                  @php
                    $towerProps = $properties->where('tower', $tower);
                    $floors = $towerProps->pluck('floor')->unique()->sortByDesc(function($floor) {
                        return is_numeric($floor) ? (int)$floor : $floor;
                    });
                  @endphp
                  
                  <div class="overflow-x-auto">
                    <div class="min-w-max p-1 space-y-2">
                      @foreach($floors as $floor)
                        <div class="flex items-center gap-4">
                          <!-- Floor Label -->
                          <div class="w-20 text-right text-xs font-bold text-gray-500 uppercase tracking-wide">
                            Floor {{ $floor }}
                          </div>
                          
                          <!-- Units row -->
                          <div class="flex gap-2">
                            @php
                              $floorProps = $towerProps->where('floor', $floor)->sortBy('unit_number');
                            @endphp
                            @foreach($floorProps as $prop)
                              @php
                                $statusBg = 'bg-emerald-500 hover:bg-emerald-600';
                                if ($prop->inventory_status === 'sold') $statusBg = 'bg-rose-500 hover:bg-rose-600';
                                elseif ($prop->inventory_status === 'reserved') $statusBg = 'bg-amber-500 hover:bg-amber-600';
                                elseif ($prop->inventory_status === 'blocked') $statusBg = 'bg-gray-900 hover:bg-gray-950';
                              @endphp
                              
                              <div class="relative group">
                                <a href="{{ route('admin.properties.edit', $prop) }}" class="inline-flex h-11 w-16 items-center justify-center rounded-lg font-bold text-xs text-white transition-all shadow-sm {{ $statusBg }}">
                                  {{ $prop->unit_number }}
                                </a>
                                
                                <!-- Hover Tooltip -->
                                <div class="absolute bottom-full left-1/2 z-10 mb-2 w-48 -translate-x-1/2 rounded-lg bg-gray-900 p-2 text-xs text-white opacity-0 shadow-lg transition-opacity pointer-events-none group-hover:opacity-100">
                                  <div class="font-bold border-b border-gray-700 pb-1 mb-1">{{ $prop->title }}</div>
                                  <div>Facing: {{ $prop->facing ?: 'N/A' }}</div>
                                  <div>Price: {{ is_numeric($prop->price) ? format_price($prop->price) : 'N/A' }}</div>
                                  <div>Size: {{ $prop->size ? $prop->size . ' sqft' : 'N/A' }}</div>
                                  <div class="text-[10px] text-gray-400 mt-1 uppercase">Status: {{ $prop->inventory_status }}</div>
                                </div>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>

          <!-- Tab 3: Documents -->
          <div x-show="activeTab === 'documents'" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-lg font-bold text-gray-900 dark:text-white">Project Documents</h2>
              <button class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-brand-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Upload Document
              </button>
            </div>
            
            <div class="text-center py-8 text-gray-500">
              <p class="text-sm">No documents uploaded for this project yet.</p>
            </div>
          </div>

          <!-- Tab 4: Sales Analytics -->
          <div x-show="activeTab === 'analytics'" class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Sales Analytics Summary</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
              <div class="border border-gray-100 rounded-xl p-5 dark:border-gray-800">
                <span class="text-xs text-gray-400 uppercase font-semibold">Inventory Allocation</span>
                <div class="mt-4 flex items-center justify-between">
                  <span class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $unitsCount > 0 ? round(($soldCount / $unitsCount) * 100, 1) : 0 }}%
                  </span>
                  <span class="text-xs text-gray-500">Sold out</span>
                </div>
              </div>
              <div class="border border-gray-100 rounded-xl p-5 dark:border-gray-800">
                <span class="text-xs text-gray-400 uppercase font-semibold">Total Reserved Value</span>
                <div class="mt-4 flex items-center justify-between">
                  <span class="text-2xl font-bold text-gray-900 dark:text-white">
                    ₹ 0.0
                  </span>
                  <span class="text-xs text-gray-500">Value of reserved units</span>
                </div>
              </div>
              <div class="border border-gray-100 rounded-xl p-5 dark:border-gray-800">
                <span class="text-xs text-gray-400 uppercase font-semibold">Total Revenue Collected</span>
                <div class="mt-4 flex items-center justify-between">
                  <span class="text-2xl font-bold text-gray-900 dark:text-white">
                    ₹ 0.0
                  </span>
                  <span class="text-xs text-emerald-500 font-semibold">₹ 0.0 Pending</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>
@endsection
