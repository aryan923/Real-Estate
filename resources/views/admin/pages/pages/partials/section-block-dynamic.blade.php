@php
  $title = $title ?? ($section['title'] ?? 'Custom Section');
  $fields = $fields ?? ($section['fields'] ?? []);
@endphp

<div class="section-block-item rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-6">
  <input type="hidden" name="sections[{{ $index }}][title]" value="{{ $title }}">

  <div class="flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
    <h3 class="text-md font-bold text-gray-800 dark:text-gray-200">
      Section: {{ $title }}
    </h3>
    <div class="flex gap-2">
      <button type="button" class="edit-section-btn rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">
        Edit Fields
      </button>
      <button type="button" class="remove-section-btn rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">
        Remove Section
      </button>
    </div>
  </div>

  <div class="space-y-4">
    @foreach($fields as $fIndex => $f)
      @php
        $fLabel = $f['label'] ?? '';
        $fType = $f['type'] ?? 'text';
        $fName = $f['name'] ?? \Illuminate\Support\Str::slug($fLabel);
        $fValue = $f['value'] ?? '';
      @endphp
      
      <div class="field-item grid grid-cols-1 gap-1" data-field-index="{{ $fIndex }}">
        <input type="hidden" class="f-label" name="sections[{{ $index }}][fields][{{ $fIndex }}][label]" value="{{ $fLabel }}">
        <input type="hidden" class="f-type" name="sections[{{ $index }}][fields][{{ $fIndex }}][type]" value="{{ $fType }}">
        <input type="hidden" class="f-name" name="sections[{{ $index }}][fields][{{ $fIndex }}][name]" value="{{ $fName }}">

        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
          {{ $fLabel }} <span class="text-xs text-gray-400 font-normal">({{ $fType }})</span>
        </label>
        
        @if($fType === 'text')
          <input type="text" name="sections[{{ $index }}][fields][{{ $fIndex }}][value]" value="{{ $fValue }}" class="f-value w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        
        @elseif($fType === 'textarea')
          <textarea name="sections[{{ $index }}][fields][{{ $fIndex }}][value]" rows="3" class="f-value w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ $fValue }}</textarea>
        
        @elseif($fType === 'image')
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label class="mb-1 block text-xs text-gray-400 font-normal">Upload File</label>
              @if(!empty($fValue))
                <input type="hidden" name="sections[{{ $index }}][fields][{{ $fIndex }}][image_current]" value="{{ $fValue }}">
                <div class="mb-2 flex items-center gap-2">
                  <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($fValue) }}" class="h-10 w-16 rounded border object-cover">
                  <span class="text-xs text-gray-400">Current Image</span>
                </div>
              @endif
              <input type="file" name="sections[{{ $index }}][fields][{{ $fIndex }}][image]" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
              <label class="mb-1 block text-xs text-gray-400 font-normal">External URL</label>
              <input type="text" name="sections[{{ $index }}][fields][{{ $fIndex }}][image_url]" value="{{ $fValue }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
          </div>
        @endif
      </div>
    @endforeach
  </div>
</div>
