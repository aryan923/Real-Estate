@php
  $type = $type ?? ($section['type'] ?? '');
@endphp

<div class="section-block-item rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-6">
  <input type="hidden" name="sections[{{ $index }}][type]" value="{{ $type }}">

  <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
    <div class="flex items-center gap-2.5">
      <span class="rounded bg-brand-50 px-2.5 py-1 text-xs font-semibold uppercase text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
        {{ $type }}
      </span>
      <h3 class="text-md font-bold text-gray-800 dark:text-gray-200">
        @if($type === 'hero') Hero Header
        @elseif($type === 'about') About Us Info Blocks
        @elseif($type === 'team') Meet Our Team List
        @elseif($type === 'testimonials') Testimonials Slider
        @elseif($type === 'faqs') Frequently Asked Questions
        @elseif($type === 'stats') Our Team Stats
        @elseif($type === 'contact') Get In Touch Form
        @endif
      </h3>
    </div>
    <button type="button" class="remove-section-btn rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">
      Remove Section
    </button>
  </div>

  @if($type === 'hero')
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Hero Title</label>
        <input type="text" name="sections[{{ $index }}][title]" value="{{ $section['title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Hero Subtitle</label>
        <input type="text" name="sections[{{ $index }}][subtitle]" value="{{ $section['subtitle'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
    </div>
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Background Image (File Upload)</label>
        @if(!empty($section['image']))
          <input type="hidden" name="sections[{{ $index }}][image_current]" value="{{ $section['image'] }}">
          <div class="mb-2 flex items-center gap-2">
            <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($section['image']) }}" class="h-10 w-16 rounded border object-cover">
            <span class="text-xs text-gray-400">Current Image</span>
          </div>
        @endif
        <input type="file" name="sections[{{ $index }}][image]" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Image URL (or use External Link)</label>
        <input type="text" name="sections[{{ $index }}][image_url]" value="{{ $section['image'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
    </div>

  @elseif($type === 'about')
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Section Title</label>
        <input type="text" name="sections[{{ $index }}][title]" value="{{ $section['title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Section Subtitle</label>
        <textarea name="sections[{{ $index }}][subtitle]" rows="2" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ $section['subtitle'] ?? '' }}</textarea>
      </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 border-t border-gray-100 pt-4 dark:border-gray-800">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Mission Title</label>
        <input type="text" name="sections[{{ $index }}][mission_title]" value="{{ $section['mission_title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Mission Description</label>
        <textarea name="sections[{{ $index }}][mission_desc]" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ $section['mission_desc'] ?? '' }}</textarea>
      </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 border-t border-gray-100 pt-4 dark:border-gray-800">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Vision Title</label>
        <input type="text" name="sections[{{ $index }}][vision_title]" value="{{ $section['vision_title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Vision Description</label>
        <textarea name="sections[{{ $index }}][vision_desc]" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ $section['vision_desc'] ?? '' }}</textarea>
      </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 border-t border-gray-100 pt-4 dark:border-gray-800">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Values Title</label>
        <input type="text" name="sections[{{ $index }}][values_title]" value="{{ $section['values_title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Values Description</label>
        <textarea name="sections[{{ $index }}][values_desc]" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ $section['values_desc'] ?? '' }}</textarea>
      </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 border-t border-gray-100 pt-4 dark:border-gray-800">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Resources Title</label>
        <input type="text" name="sections[{{ $index }}][resources_title]" value="{{ $section['resources_title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Resources Description</label>
        <textarea name="sections[{{ $index }}][resources_desc]" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ $section['resources_desc'] ?? '' }}</textarea>
      </div>
    </div>

  @elseif($type === 'team' || $type === 'testimonials')
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Section Title</label>
        <input type="text" name="sections[{{ $index }}][title]" value="{{ $section['title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Section Subtitle</label>
        <input type="text" name="sections[{{ $index }}][subtitle]" value="{{ $section['subtitle'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
    </div>

  @elseif($type === 'faqs')
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">FAQ Title</label>
        <input type="text" name="sections[{{ $index }}][title]" value="{{ $section['title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">FAQ Subtitle</label>
        <input type="text" name="sections[{{ $index }}][subtitle]" value="{{ $section['subtitle'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
    </div>

    <div class="space-y-4 border-t border-gray-100 pt-4 dark:border-gray-800">
      <div class="flex items-center justify-between">
        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">FAQ Items</label>
        <button type="button" class="add-faq-item-btn inline-flex items-center gap-1.5 rounded bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-600 hover:bg-brand-100 transition" data-sec-index="{{ $index }}">
          + Add FAQ Item
        </button>
      </div>

      <div class="faq-items-list space-y-4">
        @php
          $faqItems = $section['items'] ?? [];
        @endphp
        @foreach($faqItems as $itemIndex => $item)
          <div class="faq-item grid gap-4 sm:grid-cols-12 bg-gray-50/50 p-4 rounded-xl border border-gray-200/60 dark:bg-gray-900/10 dark:border-gray-800 relative">
            <div class="sm:col-span-5">
              <label class="mb-1 block text-xs font-semibold text-gray-500 uppercase">Question</label>
              <input type="text" name="sections[{{ $index }}][items][{{ $itemIndex }}][question]" value="{{ $item['question'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="sm:col-span-6">
              <label class="mb-1 block text-xs font-semibold text-gray-500 uppercase">Answer</label>
              <textarea name="sections[{{ $index }}][items][{{ $itemIndex }}][answer]" rows="2" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ $item['answer'] ?? '' }}</textarea>
            </div>
            <div class="sm:col-span-1 flex items-end justify-center pb-1.5">
              <button type="button" class="remove-faq-item-btn rounded-lg border border-red-200 bg-red-50 p-2.5 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">
                Delete
              </button>
            </div>
          </div>
        @endforeach
      </div>
    </div>

  @elseif($type === 'stats')
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Stats Section Title</label>
        <input type="text" name="sections[{{ $index }}][title]" value="{{ $section['title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Stats Section Subtitle</label>
        <input type="text" name="sections[{{ $index }}][subtitle]" value="{{ $section['subtitle'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
    </div>

    <div class="space-y-4 border-t border-gray-100 pt-4 dark:border-gray-800">
      <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Stat Items (3 items)</label>
      @php
        $statsItems = $section['items'] ?? [];
      @endphp
      <div class="grid gap-4 sm:grid-cols-3">
        @for($i = 0; $i < 3; $i++)
          <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-200/60 dark:bg-gray-900/10 dark:border-gray-800 space-y-3">
            <div>
              <label class="mb-1 block text-xs font-semibold text-gray-500 uppercase">Value {{ $i + 1 }}</label>
              <input type="text" name="sections[{{ $index }}][items][{{ $i }}][value]" value="{{ $statsItems[$i]['value'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
              <label class="mb-1 block text-xs font-semibold text-gray-500 uppercase">Label {{ $i + 1 }}</label>
              <input type="text" name="sections[{{ $index }}][items][{{ $i }}][label]" value="{{ $statsItems[$i]['label'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
          </div>
        @endfor
      </div>
    </div>

  @elseif($type === 'contact')
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Contact Section Title</label>
        <input type="text" name="sections[{{ $index }}][title]" value="{{ $section['title'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Contact Section Subtitle</label>
        <input type="text" name="sections[{{ $index }}][subtitle]" value="{{ $section['subtitle'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
    </div>
    <div class="grid gap-6 sm:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Side Image (File Upload)</label>
        @if(!empty($section['image']))
          <input type="hidden" name="sections[{{ $index }}][image_current]" value="{{ $section['image'] }}">
          <div class="mb-2 flex items-center gap-2">
            <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($section['image']) }}" class="h-10 w-16 rounded border object-cover">
            <span class="text-xs text-gray-400">Current Image</span>
          </div>
        @endif
        <input type="file" name="sections[{{ $index }}][image]" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
      <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Side Image URL (or use External Link)</label>
        <input type="text" name="sections[{{ $index }}][image_url]" value="{{ $section['image'] ?? '' }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
      </div>
    </div>
  @endif
</div>
