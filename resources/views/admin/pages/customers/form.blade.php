@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Customer' : 'Add Customer')
@section('page', 'customers')

@section('content')
  @include('admin.partials.admin-header')

  <main class="pb-10">
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $mode === 'edit' ? 'Edit Customer' : 'Add Customer' }}
              </h1>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $mode === 'edit' ? 'Update the customer profile details below.' : 'Create a new customer profile record.' }}
              </p>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
              Back to List
            </a>
          </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <form method="POST" action="{{ $mode === 'edit' ? route('admin.customers.update', $customer) : route('admin.customers.store') }}">
            @csrf
            @if ($mode === 'edit')
              @method('PUT')
            @endif

            @if ($errors->any())
              <div class="rounded-lg border border-red-200 bg-red-50 p-4 mb-6 text-sm text-red-700 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
                <div class="font-semibold mb-2">Please fix the following errors:</div>
                <ul class="list-disc list-inside space-y-1">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name', $customer['name'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" required value="{{ old('email', $customer['email'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="phone" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Phone Number</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $customer['phone'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="occupation" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Occupation</label>
                <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $customer['occupation'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="pan_number" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">PAN Card Number</label>
                <input type="text" name="pan_number" id="pan_number" placeholder="e.g. ABCDE1234F" value="{{ old('pan_number', $customer['pan_number'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="aadhaar_number" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Aadhaar Card Number</label>
                <input type="text" name="aadhaar_number" id="aadhaar_number" placeholder="e.g. 1234-5678-9012" value="{{ old('aadhaar_number', $customer['aadhaar_number'] ?? '') }}" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
              </div>

              <div>
                <label for="budget_range" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Budget Range</label>
                <select name="budget_range" id="budget_range" class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                  <option value="">Select Budget Range</option>
                  <option value="Under 30 Lakhs" {{ old('budget_range', $customer['budget_range'] ?? '') === 'Under 30 Lakhs' ? 'selected' : '' }}>Under 30 Lakhs</option>
                  <option value="30 Lakhs - 60 Lakhs" {{ old('budget_range', $customer['budget_range'] ?? '') === '30 Lakhs - 60 Lakhs' ? 'selected' : '' }}>30 Lakhs - 60 Lakhs</option>
                  <option value="60 Lakhs - 1 Crore" {{ old('budget_range', $customer['budget_range'] ?? '') === '60 Lakhs - 1 Crore' ? 'selected' : '' }}>60 Lakhs - 1 Crore</option>
                  <option value="1 Crore - 2 Crore" {{ old('budget_range', $customer['budget_range'] ?? '') === '1 Crore - 2 Crore' ? 'selected' : '' }}>1 Crore - 2 Crore</option>
                  <option value="Above 2 Crore" {{ old('budget_range', $customer['budget_range'] ?? '') === 'Above 2 Crore' ? 'selected' : '' }}>Above 2 Crore</option>
                </select>
              </div>

              <div>
                <label for="interested_projects" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Interested Projects</label>
                <select name="interested_projects[]" id="interested_projects" multiple class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white h-24">
                  @php
                    $selectedProjects = old('interested_projects', $customer->interested_projects ?? []);
                  @endphp
                  @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ in_array($p->id, $selectedProjects) ? 'selected' : '' }}>{{ $p->name }}</option>
                  @endforeach
                </select>
                <span class="text-[10px] text-gray-400 mt-1 block">Hold Cmd/Ctrl to select multiple projects</span>
              </div>
            </div>

            <!-- Family Members Section -->
            <div class="mt-8 border-t border-gray-100 dark:border-gray-800 pt-6">
              <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Family Members</h3>
              <div x-data="{ members: {{ json_encode(old('family_members', $customer->family_members ?? [])) }} || [] }">
                <div class="space-y-3">
                  <template x-for="(member, index) in members" :key="index">
                    <div class="flex items-center gap-3">
                      <input type="text" :name="'family_members['+index+'][name]'" x-model="member.name" placeholder="Member Name" required class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
                      <input type="text" :name="'family_members['+index+'][relation]'" x-model="member.relation" placeholder="Relation (e.g. Spouse)" required class="flex-1 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:text-white" />
                      <button type="button" @click="members.splice(index, 1)" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 dark:border-red-600/30 dark:bg-red-500/10 dark:text-red-400">
                        &times;
                      </button>
                    </div>
                  </template>
                </div>
                <button type="button" @click="members.push({name: '', relation: ''})" class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-200 px-4 py-2 text-xs font-semibold mt-3">
                  + Add Family Member
                </button>
              </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
              <a href="{{ route('admin.customers.index') }}" class="rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                Cancel
              </a>
              <button type="submit" class="rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-brand-600">
                {{ $mode === 'edit' ? 'Save Changes' : 'Create Customer' }}
              </button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection
