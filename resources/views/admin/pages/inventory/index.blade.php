@extends('admin.layouts.app')

@section('title', 'Inventory Grid')
@section('page', 'inventory')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10" x-effect="document.body.classList.toggle('overflow-hidden', drawerOpen)" x-data="{ 
    drawerOpen: false, 
    loading: false, 
    statusFilter: 'all',
    unit: {}, 
    booking: null,
    fetchUnit(id) {
      this.loading = true;
      this.drawerOpen = true;
      this.unit = {};
      this.booking = null;
      fetch(`/admin/inventory/unit/${id}`)
        .then(res => {
          if (!res.ok) throw new Error('Failed to load unit details');
          return res.json();
        })
        .then(data => {
          if (data.success) {
            this.unit = data.unit;
            this.booking = data.booking;
          } else {
            alert(data.message || 'Could not fetch unit details');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Error loading unit details.');
        })
        .finally(() => {
          this.loading = false;
        });
    },
    reserveUnit(action) {
      if (!confirm(`Are you sure you want to ${action} this unit?`)) return;
      fetch(`/admin/inventory/reserve/${this.unit.id}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ action: action })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          window.location.reload();
        } else {
          alert(data.message || 'Action failed');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Server error occurred.');
      });
    }
  }">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        
        <!-- Filter Header Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Inventory Grid</h1>
              <p class="text-xs text-gray-500">Real-time tower and floor unit allocation matrix.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
              <!-- Project Selector -->
              <div class="flex items-center gap-2">
                <label for="project_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Project:</label>
                <select name="project_id" id="project_id" onchange="const t = document.getElementById('tower'); if (t) t.value = ''; this.form.submit()" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                  @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ $p->id == $selectedProjectId ? 'selected' : '' }}>{{ $p->name }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Tower Selector -->
              @if($towers->isNotEmpty())
                <div class="flex items-center gap-2">
                  <label for="tower" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tower:</label>
                  <select name="tower" id="tower" onchange="this.form.submit()" class="h-10 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                    @foreach($towers as $t)
                      <option value="{{ $t }}" {{ $t == $selectedTower ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                  </select>
                </div>
              @endif
            </div>
          </form>
        </div>

        <!-- Inventory KPI Summary Cards -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
          <!-- Total Units -->
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-xs text-gray-400 uppercase font-semibold">Total Units</span>
            <div class="text-2xl font-black text-gray-900 dark:text-white mt-2">{{ $statusCounts['total'] ?? 0 }}</div>
          </div>

          <!-- Available (Faded Mint Green) -->
          <div class="rounded-2xl border p-5 shadow-sm transition" style="border-color: #a8dab5; background-color: rgba(230, 244, 234, 0.5);">
            <span class="text-xs font-bold uppercase flex items-center gap-1.5" style="color: #137333;">
              <span class="h-2 w-2 rounded-full" style="background-color: #137333;"></span> Available
            </span>
            <div class="text-2xl font-black mt-2" style="color: #137333;">{{ $statusCounts['available'] ?? 0 }}</div>
          </div>

          <!-- Reserved (Faded Amber) -->
          <div class="rounded-2xl border p-5 shadow-sm transition" style="border-color: #fde68a; background-color: rgba(254, 243, 199, 0.5);">
            <span class="text-xs font-bold uppercase flex items-center gap-1.5" style="color: #92400e;">
              <span class="h-2 w-2 rounded-full" style="background-color: #92400e;"></span> Reserved
            </span>
            <div class="text-2xl font-black mt-2" style="color: #92400e;">{{ $statusCounts['reserved'] ?? 0 }}</div>
          </div>

          <!-- Sold (Faded Pink/Red) -->
          <div class="rounded-2xl border p-5 shadow-sm transition" style="border-color: #fad2cf; background-color: rgba(252, 232, 230, 0.5);">
            <span class="text-xs font-bold uppercase flex items-center gap-1.5" style="color: #c5221f;">
              <span class="h-2 w-2 rounded-full" style="background-color: #c5221f;"></span> Sold
            </span>
            <div class="text-2xl font-black mt-2" style="color: #c5221f;">{{ $statusCounts['sold'] ?? 0 }}</div>
          </div>

          <!-- Blocked (Charcoal Black) -->
          <div class="rounded-2xl border p-5 shadow-sm transition dark:bg-black/60" style="border-color: #3c4043; background-color: #202124;">
            <span class="text-xs font-bold uppercase flex items-center gap-1.5 text-gray-200">
              <span class="h-2 w-2 rounded-full bg-white"></span> Blocked
            </span>
            <div class="text-2xl font-black mt-2 text-white">{{ $statusCounts['blocked'] ?? 0 }}</div>
          </div>
        </div>

        <!-- Inventory Visual Grid -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6 gap-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
              {{ $selectedProject?->name ?: 'No Project Selected' }} - {{ $selectedTower ?: 'No Tower' }}
            </h2>
            
            <!-- Interactive Status Filter Pills (BookMyShow Faded Aesthetics) -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
              <button type="button" @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900 font-bold' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                All ({{ $statusCounts['total'] ?? 0 }})
              </button>
              <button type="button" @click="statusFilter = 'available'" :style="statusFilter === 'available' ? 'background-color: #e6f4ea !important; color: #137333 !important; border: 1px solid #a8dab5 !important; font-weight: 700;' : 'background-color: rgba(230, 244, 234, 0.4) !important; color: #137333 !important; border: 1px solid transparent;'" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 font-semibold cursor-pointer">
                <span class="h-2 w-2 rounded-full" style="background-color: #137333;"></span> Available ({{ $statusCounts['available'] ?? 0 }})
              </button>
              <button type="button" @click="statusFilter = 'reserved'" :style="statusFilter === 'reserved' ? 'background-color: #fef3c7 !important; color: #92400e !important; border: 1px solid #fde68a !important; font-weight: 700;' : 'background-color: rgba(254, 243, 199, 0.4) !important; color: #92400e !important; border: 1px solid transparent;'" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 font-semibold cursor-pointer">
                <span class="h-2 w-2 rounded-full" style="background-color: #92400e;"></span> Reserved ({{ $statusCounts['reserved'] ?? 0 }})
              </button>
              <button type="button" @click="statusFilter = 'sold'" :style="statusFilter === 'sold' ? 'background-color: #fce8e6 !important; color: #c5221f !important; border: 1px solid #fad2cf !important; font-weight: 700;' : 'background-color: rgba(252, 232, 230, 0.4) !important; color: #c5221f !important; border: 1px solid transparent;'" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 font-semibold cursor-pointer">
                <span class="h-2 w-2 rounded-full" style="background-color: #c5221f;"></span> Sold ({{ $statusCounts['sold'] ?? 0 }})
              </button>
              <button type="button" @click="statusFilter = 'blocked'" :style="statusFilter === 'blocked' ? 'background-color: #202124 !important; color: #e8eaed !important; border: 1px solid #3c4043 !important; font-weight: 700;' : 'background-color: rgba(32, 33, 36, 0.15) !important; color: #202124 !important; border: 1px solid transparent;'" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 font-semibold cursor-pointer dark:text-gray-300">
                <span class="h-2 w-2 rounded-full" style="background-color: #3c4043;"></span> Blocked ({{ $statusCounts['blocked'] ?? 0 }})
              </button>
            </div>
          </div>

          @if($properties->isEmpty())
            <div class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">No Properties Available</h3>
              <p class="mt-1 text-sm text-gray-500">No properties/units have been registered for this project yet.</p>
            </div>
          @else
            @php
              $towerProps = $properties->where('tower', $selectedTower);
              $floors = $towerProps->pluck('floor')->unique()->sortByDesc(function($floor) {
                  return is_numeric($floor) ? (int)$floor : $floor;
              });
            @endphp

            <div class="overflow-x-auto">
              <div class="min-w-max p-1 space-y-3">
                @foreach($floors as $floor)
                  <div class="flex items-center gap-6">
                    <!-- Floor label -->
                    <div class="w-24 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                      Floor {{ $floor }}
                    </div>

                    <!-- Unit grids -->
                    <div class="flex gap-2 flex-wrap">
                      @php
                        $floorProps = $towerProps->where('floor', $floor)->sortBy('unit_number');
                      @endphp
                      @foreach($floorProps as $prop)
                        @php
                          // Soft Faded Mint Green (Available)
                          $statusStyle = 'background-color: #e6f4ea !important; color: #137333 !important; border: 1px solid #a8dab5 !important;';
                          
                          if ($prop->inventory_status === 'sold') {
                              // Soft Faded Pink Red (Sold)
                              $statusStyle = 'background-color: #fce8e6 !important; color: #c5221f !important; border: 1px solid #fad2cf !important;';
                          } elseif ($prop->inventory_status === 'reserved') {
                              // Soft Faded Amber (Reserved)
                              $statusStyle = 'background-color: #fef3c7 !important; color: #92400e !important; border: 1px solid #fde68a !important;';
                          } elseif ($prop->inventory_status === 'blocked') {
                              // Dark Charcoal Black (Blocked)
                              $statusStyle = 'background-color: #202124 !important; color: #e8eaed !important; border: 1px solid #3c4043 !important;';
                          }
                        @endphp
                        
                        <button 
                          type="button" 
                          @click="fetchUnit({{ $prop->id }})" 
                          style="{{ $statusStyle }}"
                          :class="statusFilter !== 'all' && statusFilter !== '{{ $prop->inventory_status }}' ? 'opacity-20 grayscale scale-95 pointer-events-none transition-all duration-200' : 'opacity-100 scale-100 shadow-xs cursor-pointer hover:scale-105 transition-all duration-200'"
                          class="h-12 w-20 flex flex-col justify-center items-center rounded-xl font-bold border transition-all"
                        >
                          <span class="text-xs font-bold">{{ $prop->unit_number }}</span>
                          <span class="text-[8px] font-semibold uppercase opacity-75">{{ $prop->facing ?: 'N/A' }}</span>
                        </button>
                      @endforeach
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>

      </div>
    </div>

    <!-- Slide-over Drawer Panel -->
    <div 
      x-show="drawerOpen" 
      @keydown.window.escape="drawerOpen = false"
      class="fixed inset-0 z-50 overflow-hidden flex justify-end" 
      style="display: none;"
    >
      <!-- Overlay backdrop background -->
      <div 
        x-show="drawerOpen"
        x-transition:enter="ease-in-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="drawerOpen = false"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"
      ></div>

      <!-- Slide panel card (z-10 on top of backdrop) -->
      <div 
        x-show="drawerOpen"
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in-out duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative z-10 w-full max-w-md h-full bg-white shadow-2xl dark:bg-gray-900 flex flex-col"
      >
        <!-- Drawer Header (Fixed top) -->
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 p-6 flex-shrink-0">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white" x-text="loading ? 'Loading Unit...' : 'Unit Details'"></h2>
          <button type="button" @click="drawerOpen = false" class="text-gray-500 hover:text-gray-700 text-2xl font-bold leading-none">&times;</button>
        </div>

        <!-- Loader -->
        <div x-show="loading" class="flex-1 flex justify-center items-center py-20">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-500"></div>
        </div>

        <!-- Drawer Body (Scrollable content) -->
        <div x-show="!loading" class="flex-1 overflow-y-auto p-6 space-y-6">
          <div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white" x-text="unit.title"></h3>
            <p class="text-xs text-gray-500 mt-1" x-text="unit.project_name"></p>
          </div>

          <!-- Main Stats Matrix -->
          <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-100 dark:border-gray-800 py-4">
            <div>
              <span class="text-xs text-gray-400 uppercase font-semibold">Unit Code</span>
              <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5" x-text="unit.unit_number"></p>
            </div>
            <div>
              <span class="text-xs text-gray-400 uppercase font-semibold">Tower & Floor</span>
              <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5" x-text="(unit.tower || 'N/A') + ' / Floor ' + (unit.floor || 'N/A')"></p>
            </div>
            <div>
              <span class="text-xs text-gray-400 uppercase font-semibold">Price</span>
              <p class="text-sm font-bold text-brand-600 dark:text-brand-400 mt-0.5" x-text="unit.formatted_price"></p>
            </div>
            <div>
              <span class="text-xs text-gray-400 uppercase font-semibold">BHK / Size</span>
              <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5" x-text="(unit.bedrooms || 'N/A') + ' BHK (' + (unit.size || 'N/A') + ' sqft)'"></p>
            </div>
            <div>
              <span class="text-xs text-gray-400 uppercase font-semibold">Facing</span>
              <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5" x-text="unit.facing || 'N/A'"></p>
            </div>
            <div>
              <span class="text-xs text-gray-400 uppercase font-semibold">Parking slots</span>
              <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5" x-text="unit.parking_slots || 0"></p>
            </div>
          </div>

          <!-- Current Allocation Status Block -->
          <div class="bg-gray-50 dark:bg-white/[0.02] p-4 rounded-xl border border-gray-100 dark:border-gray-800">
            <span class="text-xs text-gray-400 uppercase font-semibold block mb-2">Current Allocation Status</span>
            <div>
              <template x-if="unit.inventory_status === 'available'">
                <span class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #e6f4ea !important; color: #137333 !important; border: 1px solid #a8dab5 !important;">Available</span>
              </template>
              <template x-if="unit.inventory_status === 'reserved'">
                <span class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #fef3c7 !important; color: #92400e !important; border: 1px solid #fde68a !important;">Reserved</span>
              </template>
              <template x-if="unit.inventory_status === 'sold'">
                <span class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #fce8e6 !important; color: #c5221f !important; border: 1px solid #fad2cf !important;">Sold</span>
              </template>
              <template x-if="unit.inventory_status === 'blocked'">
                <span class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #202124 !important; color: #e8eaed !important; border: 1px solid #3c4043 !important;">Blocked</span>
              </template>
            </div>
          </div>

          <!-- Active Booking details if reserved/sold -->
          <template x-if="booking">
            <div class="bg-gray-50 dark:bg-white/[0.02] p-4 rounded-xl border border-gray-100 dark:border-gray-800 space-y-2">
              <h4 class="text-xs font-bold uppercase text-gray-400 tracking-wider">Booking Allocation details</h4>
              <div class="text-sm flex justify-between">
                <span class="text-gray-500">Customer:</span>
                <a :href="'/admin/customers/' + booking.customer_id" class="font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400" x-text="booking.customer_name"></a>
              </div>
              <div class="text-sm flex justify-between">
                <span class="text-gray-500">Date booked:</span>
                <span class="font-semibold text-gray-700 dark:text-gray-300" x-text="booking.booking_date"></span>
              </div>
              <div class="text-sm flex justify-between">
                <span class="text-gray-500">Status:</span>
                <span class="font-semibold text-gray-700 dark:text-gray-300 uppercase" x-text="booking.status"></span>
              </div>
            </div>
          </template>
        </div>

        <!-- Drawer Footer (Fixed bottom action buttons) -->
        <div x-show="!loading" class="p-6 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 flex-shrink-0 space-y-3">
          <!-- IF available -->
          <template x-if="unit.inventory_status === 'available'">
            <div class="flex flex-col gap-3">
              <div class="flex gap-3">
                <button type="button" @click="reserveUnit('reserve')" class="flex-1 rounded-lg border border-amber-500 text-amber-600 hover:bg-amber-50 py-3 text-sm font-semibold transition dark:hover:bg-amber-500/10">
                  Reserve Unit
                </button>
                <a :href="'/admin/bookings/create?property_id=' + unit.id" class="flex-1 text-center rounded-lg bg-brand-500 text-white hover:bg-brand-600 py-3 text-sm font-semibold transition">
                  Book Unit
                </a>
              </div>
              <button type="button" @click="reserveUnit('block')" class="w-full rounded-lg border border-gray-900 text-gray-900 dark:border-gray-100 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 py-3 text-sm font-semibold transition">
                Block Unit
              </button>
            </div>
          </template>

          <!-- IF reserved -->
          <template x-if="unit.inventory_status === 'reserved'">
            <div class="flex flex-col gap-3">
              <a :href="'/admin/bookings/create?property_id=' + unit.id" class="w-full text-center rounded-lg bg-brand-500 text-white hover:bg-brand-600 py-3 text-sm font-semibold transition">
                Convert to Booking
              </a>
              <button type="button" @click="reserveUnit('release')" class="w-full rounded-lg border border-red-500 text-red-600 hover:bg-red-50 py-3 text-sm font-semibold transition dark:hover:bg-red-500/10">
                Release Reservation
              </button>
            </div>
          </template>

          <!-- IF blocked -->
          <template x-if="unit.inventory_status === 'blocked'">
            <div class="flex flex-col gap-3">
              <button type="button" @click="reserveUnit('unblock')" class="w-full rounded-lg border border-emerald-500 text-emerald-600 hover:bg-emerald-50 py-3 text-sm font-semibold transition dark:hover:bg-emerald-500/10">
                Unblock Unit (Make Available)
              </button>
            </div>
          </template>
          
          <!-- Edit property link -->
          <a :href="'/admin/properties/' + unit.id + '/edit'" class="block w-full text-center rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 py-2.5 text-sm font-semibold transition dark:border-gray-800 dark:text-gray-200 dark:hover:bg-gray-800">
            Edit Unit Specifications
          </a>
        </div>
      </div>
    </div>

  </main>
@endsection
