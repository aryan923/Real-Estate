@extends('admin.layouts.app')

@section('title', 'Create CMS Page')
@section('page', 'pages')

@section('content')
  @include('admin.partials.admin-header')

  <main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
      <div class="grid grid-cols-1 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
              <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Create Page</h1>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Assemble custom pages by choosing dynamic layout sections.</p>
            </div>
            <a href="{{ route('admin.pages') }}" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Back</a>
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

        <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf

          <!-- Page settings -->
          <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Page Settings</h2>
            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Page Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. About Us" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>
              <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Page Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" placeholder="e.g. about-us" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
              </div>
            </div>
          </section>

          <!-- Dynamic Layout Builder Header -->
          <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-wrap gap-4 items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Page Layout Sections</h2>
              <p class="text-xs text-gray-500 mt-1 font-normal">Add entirely custom sections with user-defined fields (Text, Textareas, Images) to make this page fully customizable.</p>
            </div>
            <button type="button" id="open-section-modal-btn" class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-sm font-medium text-white hover:bg-brand-600 transition">
              + Add Custom Section
            </button>
          </div>

          <!-- Dynamic Sections Container -->
          <div id="page-sections-container" class="space-y-6">
            <!-- Dynamic sections will be added here -->
          </div>

          <div class="flex items-center justify-end gap-3 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <a href="{{ route('admin.pages') }}" class="rounded-lg border border-gray-200 px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200">Cancel</a>
            <button type="submit" class="rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white hover:bg-brand-600">Save Page</button>
          </div>
        </form>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script>
    $(document).ready(function () {
      let activeSectionBlock = null;

      // Append Modal to Body to avoid container stacking/z-index issues with sidebar/header
      $('body').append(`
        <div id="section-modal" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 99999; display: none; align-items: center; justify-content: center; padding: 16px;">
          <div style="background-color: #ffffff; width: 100%; max-width: 500px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid #e5e7eb; box-sizing: border-box; text-align: left;">
            
            <!-- Modal Header -->
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f3f4f6; padding-bottom: 12px; margin-bottom: 16px;">
              <h3 id="modal-title-header" style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0;">Create Custom Section</h3>
              <button type="button" id="close-section-modal-btn" style="background: transparent; border: none; font-size: 1.5rem; color: #9ca3af; cursor: pointer; line-height: 1;">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 6px;">Section Title</label>
                <input type="text" id="modal-section-title" placeholder="e.g. Our Mission" style="width: 100%; border: 1px solid #d1d5db; background-color: #ffffff; padding: 10px 12px; font-size: 0.875rem; color: #374151; outline: none; box-sizing: border-box; border-radius: 8px;">
              </div>

              <div style="border-top: 1px solid #f3f4f6; padding-top: 16px; display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                  <label style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0;">Configure Fields</label>
                  <button type="button" id="modal-add-field-btn" style="background: transparent; border: none; font-size: 0.75rem; font-weight: 700; color: #1e73be; cursor: pointer; padding: 4px;">+ Add Field</button>
                </div>
                
                <div id="modal-fields-container" style="display: flex; flex-direction: column; gap: 8px; max-height: 240px; overflow-y: auto; padding: 4px;">
                  <!-- Field rows will go here -->
                </div>
              </div>
            </div>

            <!-- Modal Footer -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #f3f4f6; padding-top: 16px; margin-top: 24px;">
              <button type="button" id="cancel-section-modal-btn" style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 16px; font-size: 0.875rem; font-weight: 500; color: #374151; cursor: pointer;">Cancel</button>
              <button type="button" id="save-section-modal-btn" style="background-color: #1e73be; border: 1px solid #1e73be; border-radius: 8px; padding: 8px 16px; font-size: 0.875rem; font-weight: 500; color: #ffffff; cursor: pointer;">Save Section</button>
            </div>
          </div>
        </div>
      `);

      // Add Custom Section Modal controls
      $('#open-section-modal-btn').on('click', function () {
        activeSectionBlock = null;
        $('#modal-title-header').text('Create Custom Section');
        $('#section-modal').css('display', 'flex');
        $('#modal-section-title').val('');
        $('#modal-fields-container').html('');
      });

      // Edit Section / Fields
      $(document).on('click', '.edit-section-btn', function () {
        activeSectionBlock = $(this).closest('.section-block-item');
        const title = activeSectionBlock.find('input[name*="[title]"]').val();
        
        $('#modal-title-header').text('Edit Section Fields');
        $('#modal-section-title').val(title);
        $('#modal-fields-container').html('');

        // Extract existing fields and their values
        activeSectionBlock.find('.field-item').each(function () {
          const label = $(this).find('.f-label').val();
          const type = $(this).find('.f-type').val();
          let value = '';
          if (type === 'image') {
            value = $(this).find('input[name*="[image_current]"]').val() || $(this).find('input[name*="[image_url]"]').val() || '';
          } else {
            value = $(this).find('.f-value').val() || '';
          }

          const html = `
            <div class="modal-field-row" style="display: flex; gap: 8px; align-items: center; background-color: #f9fafb; padding: 8px; border-radius: 8px; border: 1px solid #e5e7eb; box-sizing: border-box; width: 100%;">
              <input type="text" class="field-label-input" style="width: 100%; border: 1px solid #d1d5db; background-color: #ffffff; padding: 6px 8px; font-size: 0.75rem; color: #374151; border-radius: 6px; outline: none; box-sizing: border-box;" placeholder="Field Label (e.g. Subtitle)" value="${label}">
              <input type="hidden" class="field-value-saved" value="${value}">
              <select class="field-type-select" style="border: 1px solid #d1d5db; background-color: #ffffff; padding: 6px 8px; font-size: 0.75rem; color: #374151; border-radius: 6px; outline: none;">
                <option value="text" ${type === 'text' ? 'selected' : ''}>Text Input</option>
                <option value="textarea" ${type === 'textarea' ? 'selected' : ''}>Textarea</option>
                <option value="image" ${type === 'image' ? 'selected' : ''}>Image File</option>
              </select>
              <button type="button" class="modal-remove-field-btn" style="background: transparent; border: none; color: #ef4444; font-size: 0.75rem; cursor: pointer; padding: 4px;">Delete</button>
            </div>
          `;
          $('#modal-fields-container').append(html);
        });

        $('#section-modal').css('display', 'flex');
      });

      $(document).on('click', '#close-section-modal-btn, #cancel-section-modal-btn', function () {
        $('#section-modal').css('display', 'none');
      });

      $(document).on('click', '#section-modal', function (e) {
        if (e.target === this) {
          $('#section-modal').css('display', 'none');
        }
      });

      // Add Field row inside modal
      $(document).on('click', '#modal-add-field-btn', function () {
        const html = `
          <div class="modal-field-row" style="display: flex; gap: 8px; align-items: center; background-color: #f9fafb; padding: 8px; border-radius: 8px; border: 1px solid #e5e7eb; box-sizing: border-box; width: 100%;">
            <input type="text" class="field-label-input" style="width: 100%; border: 1px solid #d1d5db; background-color: #ffffff; padding: 6px 8px; font-size: 0.75rem; color: #374151; border-radius: 6px; outline: none; box-sizing: border-box;" placeholder="Field Label (e.g. Subtitle)">
            <input type="hidden" class="field-value-saved" value="">
            <select class="field-type-select" style="border: 1px solid #d1d5db; background-color: #ffffff; padding: 6px 8px; font-size: 0.75rem; color: #374151; border-radius: 6px; outline: none;">
              <option value="text">Text Input</option>
              <option value="textarea">Textarea</option>
              <option value="image">Image File</option>
            </select>
            <button type="button" class="modal-remove-field-btn" style="background: transparent; border: none; color: #ef4444; font-size: 0.75rem; cursor: pointer; padding: 4px;">Delete</button>
          </div>
        `;
        $('#modal-fields-container').append(html);
      });

      // Remove Field row inside modal
      $(document).on('click', '.modal-remove-field-btn', function () {
        $(this).closest('.modal-field-row').remove();
      });

      // Save custom section from modal
      $(document).on('click', '#save-section-modal-btn', function () {
        const title = $('#modal-section-title').val();
        if (!title) {
          alert('Section Title is required!');
          return;
        }

        const fields = [];
        $('.modal-field-row').each(function () {
          const label = $(this).find('.field-label-input').val();
          const type = $(this).find('.field-type-select').val();
          const value = $(this).find('.field-value-saved').val() || '';
          if (label) {
            fields.push({ label: label, type: type, value: value });
          }
        });

        if (activeSectionBlock) {
          // Editing existing section block
          const nameAttr = activeSectionBlock.find('input[name*="[title]"]').attr('name');
          const index = nameAttr.match(/sections\[([^\]]+)\]/)[1];

          $.get('{{ route("admin.pages.create") }}', { 
            get_section_block: 1, 
            title: title, 
            index: index, 
            fields: fields 
          }, function (html) {
            activeSectionBlock.replaceWith(html);
            $('#section-modal').css('display', 'none');
          });
        } else {
          // Adding new section block
          const index = Date.now();
          $.get('{{ route("admin.pages.create") }}', { 
            get_section_block: 1, 
            title: title, 
            index: index, 
            fields: fields 
          }, function (html) {
            $('#page-sections-container').append(html);
            $('#section-modal').css('display', 'none');
          });
        }
      });

      // Remove Section from main form
      $(document).on('click', '.remove-section-btn', function () {
        if (confirm('Are you sure you want to remove this section?')) {
          $(this).closest('.section-block-item').remove();
        }
      });
    });
  </script>
@endpush
