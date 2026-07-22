@extends('admin.layouts.app')

@section('title', 'Documents Management')
@section('page', 'documents')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10" x-data="{
    activeFolder: '',
    association: 'customer',
    selectedFile: null,
    customers: {{ json_encode($customers->map(fn($c) => ['id' => $c->id, 'name' => $c->name])) }},
    projects: {{ json_encode($projects->map(fn($p) => ['id' => $p->id, 'name' => $p->name])) }},
    bookings: {{ json_encode($bookings->map(fn($b) => ['id' => $b->id, 'name' => $b->booking_no . ' (' . ($b->customer?->name ?? 'N/A') . ')'])) }},
    getFilteredEntities() {
      if (this.association === 'customer') return this.customers;
      if (this.association === 'project') return this.projects;
      if (this.association === 'booking') return this.bookings;
      return [];
    },
    handleFileDrop(e) {
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        document.getElementById('file-input').files = files;
        this.selectedFile = files[0].name;
      }
    },
    handleFileSelect(e) {
      const files = e.target.files;
      if (files.length > 0) {
        this.selectedFile = files[0].name;
      }
    }
  }">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-6">
        
        <!-- Header Panel -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Document Management</h1>
          <p class="text-xs text-gray-500">Upload and organize polymorphic verification files.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Column 1: Drag and Drop Upload Portal -->
          <div class="lg:col-span-1 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] self-start">
            <h2 class="text-md font-bold text-gray-900 dark:text-white mb-4">Upload Document</h2>
            
            <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="space-y-4">
              @csrf
              
              <!-- Drag and Drop Box -->
              <div 
                @dragover.prevent
                @drop.prevent="handleFileDrop($event)"
                @click="document.getElementById('file-input').click()"
                class="border-2 border-dashed border-gray-200 dark:border-gray-800 hover:border-brand-500 rounded-xl p-6 text-center cursor-pointer transition-colors bg-gray-50/50 dark:bg-white/[0.01]"
              >
                <input type="file" name="file" id="file-input" class="hidden" @change="handleFileSelect($event)" required />
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="mt-2 text-xs font-semibold text-gray-800 dark:text-white" x-text="selectedFile ? selectedFile : 'Drag & drop file here or click'"></p>
                <p class="text-[10px] text-gray-400 mt-1">PDF, Word, Images (Max 10MB)</p>
              </div>

              <!-- Category -->
              <div>
                <label for="category" class="block text-xs font-semibold text-gray-800 dark:text-white mb-2">Category <span class="text-red-500">*</span></label>
                <select name="category" id="category" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-xs text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="PAN">PAN Card</option>
                  <option value="Aadhaar">Aadhaar Card</option>
                  <option value="Agreement">Purchase Agreement</option>
                  <option value="Receipt">Payment Receipt</option>
                </select>
              </div>

              <!-- Associate Type -->
              <div>
                <label for="documentable_type" class="block text-xs font-semibold text-gray-800 dark:text-white mb-2">Associate With <span class="text-red-500">*</span></label>
                <select name="documentable_type" id="documentable_type" x-model="association" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-xs text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="customer">Customer Profile</option>
                  <option value="project">Project Profile</option>
                  <option value="booking">Booking record</option>
                </select>
              </div>

              <!-- Associate ID -->
              <div>
                <label for="documentable_id" class="block text-xs font-semibold text-gray-800 dark:text-white mb-2">Select Target profile <span class="text-red-500">*</span></label>
                <select name="documentable_id" id="documentable_id" required class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-xs text-gray-800 outline-none transition focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Choose item</option>
                  <template x-for="ent in getFilteredEntities()" :key="ent.id">
                    <option :value="ent.id" x-text="ent.name"></option>
                  </template>
                </select>
              </div>

              <button type="submit" class="w-full rounded-lg bg-brand-500 px-4 py-3 text-xs font-semibold text-white transition hover:bg-brand-600">
                Upload File
              </button>
            </form>
          </div>

          <!-- Column 2: Folder Directories Grid -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- Folder directories -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
              
              <!-- Folder: PAN -->
              <button 
                @click="activeFolder = (activeFolder === 'PAN' ? '' : 'PAN')"
                :class="activeFolder === 'PAN' ? 'border-blue-500 bg-blue-50/10' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-white/[0.03]'"
                class="rounded-2xl border p-5 text-left transition shadow-sm hover:border-blue-500 w-full"
              >
                <div class="h-10 w-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center dark:bg-blue-500/10 dark:text-blue-400">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                  </svg>
                </div>
                <div class="mt-4 font-bold text-xs text-gray-800 dark:text-white">PAN Cards</div>
                <div class="text-[10px] text-gray-400 mt-1">{{ isset($groupedDocs['PAN']) ? $groupedDocs['PAN']->count() : 0 }} files</div>
              </button>

              <!-- Folder: Aadhaar -->
              <button 
                @click="activeFolder = (activeFolder === 'Aadhaar' ? '' : 'Aadhaar')"
                :class="activeFolder === 'Aadhaar' ? 'border-amber-500 bg-amber-50/10' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-white/[0.03]'"
                class="rounded-2xl border p-5 text-left transition shadow-sm hover:border-amber-500 w-full"
              >
                <div class="h-10 w-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center dark:bg-amber-500/10 dark:text-amber-400">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                  </svg>
                </div>
                <div class="mt-4 font-bold text-xs text-gray-800 dark:text-white">Aadhaar Cards</div>
                <div class="text-[10px] text-gray-400 mt-1">{{ isset($groupedDocs['Aadhaar']) ? $groupedDocs['Aadhaar']->count() : 0 }} files</div>
              </button>

              <!-- Folder: Agreement -->
              <button 
                @click="activeFolder = (activeFolder === 'Agreement' ? '' : 'Agreement')"
                :class="activeFolder === 'Agreement' ? 'border-emerald-500 bg-emerald-50/10' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-white/[0.03]'"
                class="rounded-2xl border p-5 text-left transition shadow-sm hover:border-emerald-500 w-full"
              >
                <div class="h-10 w-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center dark:bg-emerald-500/10 dark:text-emerald-400">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                  </svg>
                </div>
                <div class="mt-4 font-bold text-xs text-gray-800 dark:text-white">Agreements</div>
                <div class="text-[10px] text-gray-400 mt-1">{{ isset($groupedDocs['Agreement']) ? $groupedDocs['Agreement']->count() : 0 }} files</div>
              </button>

              <!-- Folder: Receipt -->
              <button 
                @click="activeFolder = (activeFolder === 'Receipt' ? '' : 'Receipt')"
                :class="activeFolder === 'Receipt' ? 'border-rose-500 bg-rose-50/10' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-white/[0.03]'"
                class="rounded-2xl border p-5 text-left transition shadow-sm hover:border-rose-500 w-full"
              >
                <div class="h-10 w-10 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center dark:bg-rose-500/10 dark:text-rose-400">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                  </svg>
                </div>
                <div class="mt-4 font-bold text-xs text-gray-800 dark:text-white">Receipts</div>
                <div class="text-[10px] text-gray-400 mt-1">{{ isset($groupedDocs['Receipt']) ? $groupedDocs['Receipt']->count() : 0 }} files</div>
              </button>

            </div>

            <!-- Expanded Directory View (Dynamic) -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
              
              <!-- Default state when no folder selected -->
              <div x-show="!activeFolder" class="text-center py-20 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9L7 8H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-gray-800 dark:text-white">No folder selected</h3>
                <p class="mt-1 text-xs text-gray-400">Click a directory card folder above to expand list views.</p>
              </div>

              <!-- Folder lists -->
              @foreach(['PAN', 'Aadhaar', 'Agreement', 'Receipt'] as $cat)
                <div x-show="activeFolder === '{{ $cat }}'" style="display: none;">
                  <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                    {{ $cat === 'PAN' ? 'PAN Card Directory' : ($cat === 'Aadhaar' ? 'Aadhaar Card Directory' : ($cat === 'Agreement' ? 'Purchase Agreements' : 'Payment Invoices')) }}
                  </h3>

                  @if(empty($groupedDocs[$cat]) || $groupedDocs[$cat]->isEmpty())
                    <p class="text-xs text-gray-500 text-center py-10">No documents found in this directory folder.</p>
                  @else
                    <div class="overflow-x-auto">
                      <table class="min-w-full table-auto text-xs text-left">
                        <thead>
                          <tr class="bg-gray-50 dark:bg-gray-900/40 text-gray-500">
                            <th class="px-4 py-3 font-semibold uppercase">Document Name</th>
                            <th class="px-4 py-3 font-semibold uppercase">Associated Target</th>
                            <th class="px-4 py-3 font-semibold uppercase">Size</th>
                            <th class="px-4 py-3 font-semibold uppercase">Date Uploaded</th>
                            <th class="px-4 py-3 font-semibold uppercase">Actions</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                          @foreach($groupedDocs[$cat] as $doc)
                            <tr>
                              <td class="px-4 py-3 font-semibold text-gray-800 dark:text-white max-w-xs truncate" title="{{ $doc->file_name }}">
                                {{ $doc->file_name }}
                              </td>
                              <td class="px-4 py-3 text-gray-500">
                                @if($doc->documentable)
                                  <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 font-bold uppercase text-[9px] dark:bg-gray-800 dark:text-gray-300">
                                    {{ class_basename($doc->documentable_type) }}: {{ $doc->documentable->name ?? $doc->documentable->booking_no ?? 'N/A' }}
                                  </span>
                                @else
                                  <span class="text-gray-400">N/A</span>
                                @endif
                              </td>
                              <td class="px-4 py-3 text-gray-500">
                                {{ $doc->file_size ? round($doc->file_size / 1024, 1) . ' KB' : 'N/A' }}
                              </td>
                              <td class="px-4 py-3 text-gray-400">
                                {{ $doc->created_at->format('d M Y') }}
                              </td>
                              <td class="px-4 py-3">
                                <div class="flex gap-2">
                                  <a href="{{ asset($doc->file_path) }}" target="_blank" class="rounded-md border border-gray-200 px-2 py-1 hover:bg-gray-50 font-semibold dark:border-gray-800 dark:hover:bg-white/5">
                                    Download
                                  </a>
                                  <form method="POST" action="{{ route('admin.documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document file permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-200 bg-red-50 px-2 py-1 text-red-600 hover:bg-red-100 font-semibold dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400">
                                      Delete
                                    </button>
                                  </form>
                                </div>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endif
                </div>
              @endforeach

            </div>

          </div>

        </div>

      </div>
    </div>
  </main>
@endsection
