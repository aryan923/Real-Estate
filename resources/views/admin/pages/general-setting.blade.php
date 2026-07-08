@extends('admin.layouts.app')

@section('title', ($adminSiteName ?? 'Admin') . ' | General Setting')
@section('page', 'generalSetting')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6" x-data="{
    tab: 'site',
    logoPreview: '{{ !empty($settings['site_logo']) ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($settings['site_logo']) : '' }}',
    favPreview: '{{ !empty($settings['site_fav']) ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($settings['site_fav']) : '' }}',
    previewFile(event, field) {
      const file = event.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (e) => this[field] = e.target.result;
      reader.readAsDataURL(file);
    }
  }">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="col-span-1">
          <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">General Setting</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage site identity and SMTP setup.</p>
          </div>

          @if(session('status'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
              {{ session('status') }}
            </div>
          @endif

          @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-900 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
              <div class="font-semibold">Please fix the following errors:</div>
              <ul class="mt-2 list-disc pl-5 text-sm">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6 flex flex-wrap gap-3">
              <button type="button" @click="tab='site'" class="rounded-lg px-4 py-2 text-sm font-medium" :class="tab==='site' ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200'">Site Setting</button>
              <button type="button" @click="tab='smtp'" class="rounded-lg px-4 py-2 text-sm font-medium" :class="tab==='smtp' ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200'">SMTP Setting</button>
            </div>

            <form action="{{ route('admin.general-setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
              @csrf

        <div x-show="tab === 'site'" x-cloak class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div class="md:col-span-2">
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Site Name</label>
            <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Site Logo</label>
            <input type="file" name="site_logo" @change="previewFile($event, 'logoPreview')" class="block w-full text-sm text-gray-600 dark:text-gray-300" />
            <template x-if="logoPreview">
              <img :src="logoPreview" alt="Logo Preview" class="mt-3 h-16 w-16 rounded-md object-contain" />
            </template>
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Favicon</label>
            <input type="file" name="site_fav" @change="previewFile($event, 'favPreview')" class="block w-full text-sm text-gray-600 dark:text-gray-300" />
            <template x-if="favPreview">
              <img :src="favPreview" alt="Favicon Preview" class="mt-3 h-16 w-16 rounded-md object-contain" />
            </template>
          </div>
        </div>

        <div x-show="tab === 'smtp'" x-cloak class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Host</label>
            <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Port</label>
            <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Encryption</label>
            <input type="text" name="smtp_encryption" value="{{ old('smtp_encryption', $settings['smtp_encryption'] ?? '') }}" placeholder="tls or ssl" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Username</label>
            <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">SMTP Password</label>
            <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">From Address</label>
            <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address', $settings['smtp_from_address'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
          </div>
        </div>

              <div class="pt-2">
                <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
