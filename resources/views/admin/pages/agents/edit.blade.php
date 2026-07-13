@extends('admin.layouts.app')

@section('title', 'Edit Agent')
@section('page', 'agents')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Agent</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Modify real estate agent details and meta profile.</p>
            </div>
            <a href="{{ route('admin.agents') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Back</a>
          </div>
        </div>

        @if ($errors->any())
          <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-700 dark:border-rose-800 dark:bg-rose-500/10 dark:text-rose-200">
            <ul class="list-disc space-y-1 pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('admin.agents.update', ['agent' => $user->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf
          @method('PUT')

          <!-- SECTION 1: Account Credentials -->
          <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Account Details</h2>
            
            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Password (leave blank to keep current)</label>
                <input type="password" name="password" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Profile Picture / Avatar</label>
              <div class="flex items-center gap-4">
                <div id="profile-preview-container" class="{{ $user->profile_pic ? '' : 'hidden' }}">
                  <img id="profile-preview" src="{{ $user->profile_pic ? \App\Classes\ImageuploadHelper::getUploadedFileUrl($user->profile_pic) : '#' }}" class="h-16 w-16 rounded-full object-cover border" alt="Profile avatar">
                </div>
                <input type="file" name="profile_pic" id="profile-pic-input" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>
          </section>

          <!-- SECTION 2: Professional Details -->
          <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Professional Profile Info</h2>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Job Title (e.g. Senior Manager)</label>
                <input type="text" name="title" value="{{ old('title', $user->agentMeta?->title) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Company (e.g. Lux Estate)</label>
                <input type="text" name="company" value="{{ old('company', $user->agentMeta?->company) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Association Membership (e.g. National Association of Luxury...)</label>
                <input type="text" name="member_of" value="{{ old('member_of', $user->agentMeta?->member_of) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Office Address</label>
                <input type="text" name="address" value="{{ old('address', $user->agentMeta?->address) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">MLS ID (e.g. 126775 MYD - Until 12/12/2025)</label>
                <input type="text" name="mls_id" value="{{ old('mls_id', $user->agentMeta?->mls_id) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Languages Spoken (comma separated)</label>
                <input type="text" name="languages" value="{{ old('languages', $user->agentMeta?->languages) }}" placeholder="French, Spanish, English" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-3">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Years of Experience (e.g. 30 Years as Realtor)</label>
                <input type="text" name="experience_years" value="{{ old('experience_years', $user->agentMeta?->experience_years) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Reach Hours / Time (e.g. Daily, from 9 AM to 5 PM)</label>
                <input type="text" name="reach_time" value="{{ old('reach_time', $user->agentMeta?->reach_time) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Personal Awards (e.g. Best Realtor 2000-2024)</label>
                <input type="text" name="awards" value="{{ old('awards', $user->agentMeta?->awards) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Specialties & Service Areas (comma separated tags)</label>
              <input type="text" name="specialties" value="{{ old('specialties', $user->agentMeta?->specialties) }}" placeholder="New York, Commercial, Consultancy, Residential" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">About Me Bio</label>
                <textarea name="about_me" rows="5" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('about_me', $user->agentMeta?->about_me) }}</textarea>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Detailed Experience Text (e.g. 18 years as Expert Realtor...)</label>
                <textarea name="experience_details" rows="5" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('experience_details', $user->agentMeta?->experience_details) }}</textarea>
              </div>
            </div>
          </section>

          <!-- SECTION 3: Social Profile Links -->
          <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Social Profiles & Channels</h2>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Facebook URL</label>
                <input type="url" name="facebook" value="{{ old('facebook', $user->agentMeta?->facebook) }}" placeholder="https://facebook.com/..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Twitter / X URL</label>
                <input type="url" name="twitter" value="{{ old('twitter', $user->agentMeta?->twitter) }}" placeholder="https://twitter.com/..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">LinkedIn URL</label>
                <input type="url" name="linkedin" value="{{ old('linkedin', $user->agentMeta?->linkedin) }}" placeholder="https://linkedin.com/in/..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Instagram URL</label>
                <input type="url" name="instagram" value="{{ old('instagram', $user->agentMeta?->instagram) }}" placeholder="https://instagram.com/..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-3">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">YouTube URL</label>
                <input type="url" name="youtube" value="{{ old('youtube', $user->agentMeta?->youtube) }}" placeholder="https://youtube.com/..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Vimeo URL</label>
                <input type="url" name="vimeo" value="{{ old('vimeo', $user->agentMeta?->vimeo) }}" placeholder="https://vimeo.com/..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">WhatsApp Contact Link</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $user->agentMeta?->whatsapp) }}" placeholder="e.g. https://wa.me/..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
              </div>
            </div>
          </section>

          <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Update Agent</button>
            <a href="{{ route('admin.agents') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    document.getElementById('profile-pic-input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('profile-preview');
                const container = document.getElementById('profile-preview-container');
                previewImg.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
  </script>
@endpush
