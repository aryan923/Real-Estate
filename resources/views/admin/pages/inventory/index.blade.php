@extends('admin.layouts.app')

@section('title', 'Inventory Grid')
@section('page', 'inventory')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10" x-data="{ 
    drawerOpen: false, 
    loading: false, 
    unit: {}, 
    booking: null,
    fetchUnit(id) {
      this.loading = true;
      this.drawerOpen = true;
      fetch(`/admin/inventory/unit/${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            this.unit = data.unit;
            this.booking = data.booking;
          }
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
          alert(data.message);
        }
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

        <!-- Inventory Visual Grid -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
              {{ $selectedProject?->name ?: 'No Project Selected' }} - {{ $selectedTower ?: 'No Tower' }}
            </h2>
            <div class="flex flex-wrap items-center gap-3 text-xs">
              <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-emerald-500"></span> Available</span>
              <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-rose-500"></span> Sold</span>
              <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-amber-500"></span> Reserved</span>
              <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-gray-900"></span> Blocked</span>
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
                        
                        <button type="button" @click="fetchUnit({{ $prop->id }})" class="h-12 w-20 flex flex-col justify-center items-center rounded-lg font-bold text-white transition shadow-sm {{ $statusBg }}">
                          <span class="text-xs">{{ $prop->unit_number }}</span>
                          <span class="text-[8px] font-normal uppercase opacity-80">{{ $prop->facing ?: 'N/A' }}</span>
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
      class="fixed inset-0 z-99999 overflow-hidden" 
      style="display: none;"
    >
      <div class="absolute inset-0 overflow-hidden">
        <!-- Overlay background -->
        <div 
          x-show="drawerOpen"
          x-transition:enter="ease-in-out duration-300"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="ease-in-out duration-300"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          @click="drawerOpen = false"
          class="absolute inset-0 bg-gray-500/75 transition-opacity"
        ></div>

        <!-- Slide panel wrapper -->
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
          <div 
            x-show="drawerOpen"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="pointer-events-auto w-screen max-w-md bg-white p-6 shadow-xl dark:bg-gray-900"
          >
            <!-- Drawer Header -->
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
              <h2 class="text-lg font-bold text-gray-900 dark:text-white" x-text="loading ? 'Loading Unit...' : 'Unit Details'"></h2>
              <button @click="drawerOpen = false" class="text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>
            </div>

            <!-- Loader -->
            <div x-show="loading" class="flex justify-center items-center py-20">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-500"></div>
            </div>

            <!-- Drawer Content -->
            <div x-show="!loading" class="space-y-6">
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
                  <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5" x-text="unit.tower + ' / Floor ' + unit.floor"></p>
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
                  <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5" x-text="unit.parking_slots"></p>
                </div>
              </div>

              <!-- Current Status Block -->
              <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Current Allocation Status</span>
                <div class="mt-2">
                  <template x-if="unit.inventory_status === 'available'">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wide bg-emerald-500 text-white">Available</span>
                  </template>
                  <template x-if="unit.inventory_status === 'sold'">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wide bg-rose-500 text-white">Sold</span>
                  </template>
                  <template x-if="unit.inventory_status === 'reserved'">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wide bg-amber-500 text-white">Reserved</span>
                  </template>
                  <template x-if="unit.inventory_status === 'blocked'">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wide bg-gray-900 text-white">Blocked</span>
                  </template>
                </div>
              </div>

              <!-- Active Booking details if reserved/sold -->
              <template x-if="booking">
                <div class="bg-gray-50 dark:bg-white/[0.02] p-4 rounded-xl border border-gray-100 dark:border-gray-800 space-y-2">
                  <h4 class="text-xs font-bold uppercase text-gray-400 tracking-wider">Booking Allocation details</h4>
                  <div class="text-sm">
                    <span class="text-gray-500">Customer:</span>
                    <a :href="'/admin/customers/' + booking.customer_id" class="font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400" x-text="booking.customer_name"></a>
                  </div>
                  <div class="text-sm">
                    <span class="text-gray-500">Date booked:</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300" x-text="booking.booking_date"></span>
                  </div>
                  <div class="text-sm">
                    <span class="text-gray-500">Status:</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300 uppercase" x-text="booking.status"></span>
                  </div>
                </div>
              </template>

              <!-- Action buttons -->
              <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-3">
                
                <!-- IF available -->
                <template x-if="unit.inventory_status === 'available'">
                  <div class="flex gap-3">
                    <button type="button" @click="reserveUnit('reserve')" class="flex-1 rounded-lg border border-amber-500 text-amber-600 hover:bg-amber-50 py-3 text-sm font-semibold transition dark:hover:bg-amber-500/10">
                      Reserve Unit
                    </button>
                    <a :href="'/admin/bookings/create?property_id=' + unit.id" class="flex-1 text-center rounded-lg bg-brand-500 text-white hover:bg-brand-600 py-3 text-sm font-semibold transition">
                      Book Unit
                    </a>
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
                
                <!-- Edit property link -->
                <a :href="'/admin/properties/edit/' + unit.id" class="w-full text-center rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 py-3 text-sm font-semibold transition dark:border-gray-800 dark:text-gray-200 dark:hover:bg-gray-800">
                  Edit Unit Specifications
                </a>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  </main>
@endsection
