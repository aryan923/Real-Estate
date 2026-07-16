@extends('layouts.master')

@section('title', $page?->title ?? 'Page')

@section('content')
    @php
        $metadata = $page?->metadata ?? [];
        $sections = $metadata['sections'] ?? [];
        
        // Helper to find a field value in a section's fields by name
        if (!function_exists('find_field_value')) {
            function find_field_value($fields, $name, $default = '') {
                foreach ($fields as $field) {
                    if (($field['name'] ?? '') === $name) {
                        return $field['value'] ?? $default;
                    }
                }
                return $default;
            }
        }
    @endphp

    @foreach($sections as $section)
        @php
            $title = $section['title'] ?? '';
            $fields = $section['fields'] ?? [];
        @endphp

        @if($title === 'More about us')
            @php
                $heroTitle = find_field_value($fields, 'hero-title', 'More about us');
                $heroSubtitle = find_field_value($fields, 'hero-subtitle', 'Get to know our talented team');
                $heroBg = find_field_value($fields, 'hero-background-image', 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=2200&q=80');
            @endphp
            <header class="relative isolate min-h-[520px] bg-[#08192f] z-30" data-sticky-trigger>
                @include('partials.layout.topbar')

                <div class="section-shell relative z-20 py-4 sm:py-6">
                    @include('partials.layout.navbar')
                </div>

                <img src="{{ $heroBg }}" alt="Header background" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(16,24,36,0.78)_0%,rgba(20,34,49,0.72)_45%,rgba(24,36,48,0.7)_100%)]"></div>

                <div class="section-shell relative z-10 flex min-h-[390px] flex-col items-center justify-center pt-6 text-center sm:min-h-[420px]">
                    <h1 class="text-[46px] font-medium leading-tight text-white sm:text-[58px]">{{ $heroTitle }}</h1>
                    <p class="mt-2 text-[18px] text-white/95 sm:text-[22px]">{{ $heroSubtitle }}</p>
                </div>
            </header>

        @elseif($title === 'More About Us')
            @php
                $aboutSubtitle = find_field_value($fields, 'subtitle-description');
                $missionDesc = find_field_value($fields, 'our-mission-description');
                $visionDesc = find_field_value($fields, 'our-vision-description');
                $valuesDesc = find_field_value($fields, 'our-values-description');
                $resourcesDesc = find_field_value($fields, 'our-resources-description');
            @endphp
            <section class="bg-[#f3f3f3] py-18 sm:py-22">
                <div class="section-shell">
                    <div class="mx-auto max-w-4xl text-center">
                        <h2 class="section-title">{{ $title }}</h2>
                        <p class="section-copy mx-auto mt-5 max-w-3xl">
                            {{ $aboutSubtitle }}
                        </p>
                    </div>

                    <div class="mx-auto mt-12 grid max-w-5xl gap-x-16 gap-y-12 md:grid-cols-2">
                        @if($missionDesc)
                        <article>
                            <h3 class="section-title">Our Mission</h3>
                            <p class="section-copy mt-4">
                                {!! nl2br(e($missionDesc)) !!}
                            </p>
                        </article>
                        @endif

                        @if($visionDesc)
                        <article>
                            <h3 class="section-title">Our Vision</h3>
                            <p class="section-copy mt-4">
                                {!! nl2br(e($visionDesc)) !!}
                            </p>
                        </article>
                        @endif

                        @if($valuesDesc)
                        <article>
                            <h3 class="section-title">Our Values</h3>
                            <p class="section-copy mt-4">
                                {!! nl2br(e($valuesDesc)) !!}
                            </p>
                        </article>
                        @endif

                        @if($resourcesDesc)
                        <article>
                            <h3 class="section-title">Our Resources</h3>
                            <p class="section-copy mt-4">
                                {!! nl2br(e($resourcesDesc)) !!}
                            </p>
                        </article>
                        @endif
                    </div>
                </div>
            </section>

        @elseif($title === 'Meet Our Team')
            @php
                $teamSubtitle = find_field_value($fields, 'subtitle-description');
            @endphp
            @if(count($team) > 0)
            <section class="bg-[#f3f3f3] pb-20">
                <div class="section-shell">
                    <div class="mx-auto max-w-5xl text-center">
                        <h2 class="section-title">{{ $title }}</h2>
                        <p class="section-copy mx-auto mt-5 max-w-3xl">
                            {{ $teamSubtitle }}
                        </p>
                    </div>

                    <div class="mx-auto mt-10 grid max-w-5xl gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($team as $member)
                            <article class="rounded bg-white p-3 shadow-[0_1px_2px_rgba(15,23,42,0.08)] ring-1 ring-slate-200">
                                <div class="relative aspect-[4/2.5] overflow-hidden rounded bg-[#d8d8dc]">
                                    <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="h-full w-full object-cover object-center">
                                    <span class="absolute bottom-3 left-3 rounded bg-[#59be74] px-2.5 py-1 text-[12px] font-medium leading-none text-white">{{ $member['listings'] }} listings</span>
                                </div>

                                <div class="px-2 pb-2 pt-4 text-left">
                                    <h3 class="property-card-title">{{ $member['name'] }}</h3>
                                    <p class="property-card-meta mt-1">Member ID: {{ $member['member_id'] }}</p>
                                    <p class="property-card-copy mt-3">{{ $member['bio'] }}</p>

                                    <div class="property-card-meta mt-5 flex items-center justify-between text-[#667a8c]">
                                        <div class="flex items-center gap-2.5 text-[14px]">
                                            @if($member['facebook'] !== '#')
                                                <a href="{{ $member['facebook'] }}" aria-label="Facebook" class="hover:text-[#1e73be]">f</a>
                                            @endif
                                            @if($member['twitter'] !== '#')
                                                <a href="{{ $member['twitter'] }}" aria-label="X" class="hover:text-[#1e73be]">x</a>
                                            @endif
                                            @if($member['linkedin'] !== '#')
                                                <a href="{{ $member['linkedin'] }}" aria-label="LinkedIn" class="hover:text-[#1e73be]">in</a>
                                            @endif
                                            @if($member['instagram'] !== '#')
                                                <a href="{{ $member['instagram'] }}" aria-label="Instagram" class="hover:text-[#1e73be]">o</a>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2.5 text-[14px]">
                                            @if($member['phone'])
                                                <a href="tel:{{ $member['phone'] }}" aria-label="Phone" class="hover:text-[#1e73be]">☎</a>
                                            @endif
                                            @if($member['email'])
                                                <a href="mailto:{{ $member['email'] }}" aria-label="Email" class="hover:text-[#1e73be]">✉</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

        @elseif($title === 'Testimonials')
            @include('partials.home.sections.testimonials')

        @elseif($title === 'Frequently Asked Questions')
            @php
                $faqSubtitle = find_field_value($fields, 'subtitle-description');
            @endphp
            <section class="bg-[#f3f3f3] py-18 sm:py-22" id="faq">
                <div class="section-shell">
                    <div class="mx-auto max-w-4xl text-center">
                        <h2 class="section-title">{{ $title }}</h2>
                        <p class="section-copy mx-auto mt-5 max-w-3xl">
                            {{ $faqSubtitle }}
                        </p>
                    </div>

                    <div class="mx-auto mt-10 grid max-w-5xl items-start gap-5 md:grid-cols-2" data-about-faq>
                        @for($i = 1; $i <= 10; $i++)
                            @php
                                $q = find_field_value($fields, "question-{$i}");
                                $a = find_field_value($fields, "answer-{$i}");
                            @endphp
                            @if($q && $a)
                            <details class="group rounded bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.08)]" data-about-faq-item>
                                <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-[16px] font-semibold leading-7 text-[#252a31]">
                                    <span>{{ $q }}</span>
                                    <span class="mt-1 text-[14px] text-[#1f2a44] transition group-open:rotate-180">⌄</span>
                                </summary>
                                <p class="mt-3 text-[15px] leading-7 text-[#5f646c]">{{ $a }}</p>
                            </details>
                            @endif
                        @endfor
                    </div>
                </div>
            </section>

        @elseif($title === 'Our Team Stats')
            @php
                $statsSubtitle = find_field_value($fields, 'subtitle-description');
            @endphp
            <section class="bg-[#f3f3f3] pb-24">
                <div class="section-shell">
                    <div class="mx-auto max-w-5xl text-center">
                        <h2 class="section-title">{{ $title }}</h2>
                        <p class="section-copy mx-auto mt-4 max-w-3xl">
                            {{ $statsSubtitle }}
                        </p>

                        <div class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-8 text-center sm:grid-cols-3">
                            @for($i = 1; $i <= 3; $i++)
                                @php
                                    $val = find_field_value($fields, "stat-{$i}-value");
                                    $lbl = find_field_value($fields, "stat-{$i}-label");
                                @endphp
                                @if($val && $lbl)
                                <div>
                                    <p class="text-[38px] font-semibold leading-none text-[#1e73be]">{{ $val }}</p>
                                    <p class="property-card-meta mt-2 font-semibold text-[#252a31]">{{ $lbl }}</p>
                                </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </section>

        @elseif($title === 'Get in touch to plan your next transaction')
            @php
                $contactSubtitle = find_field_value($fields, 'subtitle-description');
                $contactImg = find_field_value($fields, 'side-image', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1000&q=80');
            @endphp
            <section class="bg-[#f3f3f3] pb-24">
                <div class="section-shell">
                    <div class="mx-auto mt-12 grid max-w-4xl overflow-hidden rounded bg-white shadow-[0_1px_2px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 md:grid-cols-2">
                        <div class="min-h-[430px]">
                            <img src="{{ $contactImg }}" alt="Modern building" class="h-full w-full object-cover">
                        </div>

                        <div class="p-7 sm:p-8">
                            <h3 class="section-title max-w-xs">{{ $title }}</h3>
                            <p class="section-copy mt-4">
                                {{ $contactSubtitle }}
                            </p>

                            <form id="about-contact-form" action="{{ route('save_enq') }}" method="POST" class="mt-6 space-y-4">
                                @csrf
                                <input type="hidden" name="type" value="contact">

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">First name*</label>
                                        <input type="text" name="first_name" placeholder="First name" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter your first name.</span>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Last name*</label>
                                        <input type="text" name="last_name" placeholder="Last name" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter your last name.</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Email*</label>
                                        <input type="email" name="email" placeholder="Email" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter your email address.</span>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Mobile</label>
                                        <input type="tel" name="phone" placeholder="Mobile" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter a valid phone number.</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Inquiry Type</label>
                                    <select name="enq_for" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                        <option value="">Select Inquiry Type</option>
                                        <option value="sale">Sale</option>
                                        <option value="rent">Rent</option>
                                        <option value="lease">Lease</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.03em] text-[#6b6f76]">Message</label>
                                    <textarea name="message" rows="4" placeholder="Message" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"></textarea>
                                </div>

                                <button type="submit" class="inline-flex h-10 min-w-[150px] items-center justify-center rounded bg-[#1e73be] px-6 text-[14px] font-semibold text-white hover:bg-[#0f6fcb] transition flex items-center justify-center gap-2">
                                    <span class="submit-text">Send Email</span>
                                    <svg class="spinner hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <!-- Completely Custom Sections rendering default layout -->
            <section class="bg-white py-12 sm:py-16 border-t border-gray-100 dark:bg-gray-900 dark:border-gray-800">
                <div class="section-shell">
                    @php
                        $images = [];
                        $textFields = [];
                        foreach($fields as $field) {
                            if(($field['type'] ?? '') === 'image' && !empty($field['value'])) {
                                $images[] = $field;
                            } else {
                                $textFields[] = $field;
                            }
                        }
                    @endphp

                    @if(count($images) > 0)
                        <!-- Layout with images (2 columns) -->
                        <div class="grid gap-12 md:grid-cols-2 items-center">
                            <div>
                                <img src="{{ \App\Classes\ImageuploadHelper::getUploadedFileUrl($images[0]['value']) }}" alt="{{ $title }}" class="w-full rounded-2xl shadow-lg object-cover max-h-[450px]">
                            </div>
                            <div class="space-y-6">
                                <h2 class="section-title text-left">{{ $title }}</h2>
                                @foreach($textFields as $tf)
                                    @if(!empty($tf['value']))
                                        <div class="space-y-2">
                                            <h4 class="text-sm font-semibold uppercase text-blue-600 tracking-wider">{{ $tf['label'] ?? '' }}</h4>
                                            @if(($tf['type'] ?? '') === 'textarea')
                                                <p class="section-copy text-left">{!! nl2br(e($tf['value'])) !!}</p>
                                            @else
                                                <p class="text-lg font-medium text-slate-800 dark:text-slate-200">{{ $tf['value'] }}</p>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Text only layout (Centered column) -->
                        <div class="mx-auto max-w-3xl text-center space-y-8">
                            <h2 class="section-title">{{ $title }}</h2>
                            <div class="space-y-6">
                                @foreach($textFields as $tf)
                                    @if(!empty($tf['value']))
                                        <div class="space-y-2">
                                            <h4 class="text-xs font-bold uppercase text-blue-600 tracking-widest">{{ $tf['label'] ?? '' }}</h4>
                                            @if(($tf['type'] ?? '') === 'textarea')
                                                <p class="section-copy mx-auto max-w-2xl">{!! nl2br(e($tf['value'])) !!}</p>
                                            @else
                                                <p class="text-xl font-semibold text-slate-800 dark:text-slate-200">{{ $tf['value'] }}</p>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    @endforeach

    @include('partials.layout.footer')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module">
$(document).ready(function() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function clearErrors(form) {
        $(form).find('.error-msg').addClass('hidden');
        $(form).find('input, select, textarea').removeClass('border-red-500').addClass('border-slate-200');
    }

    function showFieldError(inputEl, errorText) {
        inputEl.addClass('border-red-500').removeClass('border-slate-200');
        const errorEl = inputEl.parent().find('.error-msg');
        if (errorEl.length) {
            if (errorText) {
                errorEl.text(errorText);
            }
            errorEl.removeClass('hidden');
        }
    }

    $('#about-contact-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const $form = $(form);
        clearErrors(form);

        let isValid = true;

        const firstNameInput = $form.find('input[name="first_name"]');
        if (!firstNameInput.val().trim()) {
            showFieldError(firstNameInput, 'First name is required.');
            isValid = false;
        }

        const lastNameInput = $form.find('input[name="last_name"]');
        if (!lastNameInput.val().trim()) {
            showFieldError(lastNameInput, 'Last name is required.');
            isValid = false;
        }

        const emailInput = $form.find('input[name="email"]');
        const emailVal = emailInput.val().trim();
        if (!emailVal) {
            showFieldError(emailInput, 'Email is required.');
            isValid = false;
        } else if (!emailRegex.test(emailVal)) {
            showFieldError(emailInput, 'Please enter a valid email address.');
            isValid = false;
        }

        const phoneInput = $form.find('input[name="phone"]');
        const phoneVal = phoneInput.val().trim();
        if (phoneVal && phoneVal.length > 20) {
            showFieldError(phoneInput, 'Phone number must be under 20 characters.');
            isValid = false;
        }

        if (!isValid) return;

        const submitBtn = $form.find('button[type="submit"]');
        const spinner = submitBtn.find('.spinner');
        const submitText = submitBtn.find('.submit-text');

        submitBtn.prop('disabled', true);
        spinner.removeClass('hidden');
        if (submitText.length) submitText.css('opacity', '0.5');

        // Combine first and last name for backend 'name' field
        const formDataArray = $form.serializeArray();
        const firstName = formDataArray.find(item => item.name === 'first_name')?.value || '';
        const lastName = formDataArray.find(item => item.name === 'last_name')?.value || '';
        const combinedName = (firstName + ' ' + lastName).trim();
        
        // Remove first_name and last_name from data to send, add combined name
        const dataToSend = formDataArray.filter(item => item.name !== 'first_name' && item.name !== 'last_name');
        dataToSend.push({ name: 'name', value: combinedName });

        $.ajax({
            url: "{{ route('save_enq') }}",
            type: 'POST',
            data: $.param(dataToSend),
            dataType: 'json',
            success: function(response) {
                submitBtn.prop('disabled', false);
                spinner.addClass('hidden');
                if (submitText.length) submitText.css('opacity', '1');

                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message || 'Submitted successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e73be'
                    });
                    
                    // Reset inputs
                    $form.find('input[type="text"], input[type="email"], input[type="tel"]').val('');
                    $form.find('select').val('');
                    $form.find('textarea').val('');
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'Submission failed. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e73be'
                    });
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false);
                spinner.addClass('hidden');
                if (submitText.length) submitText.css('opacity', '1');
                
                let errorText = 'Submission failed. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorText = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: errorText,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#1e73be'
                });
            }
        });
    });
});
</script>
@endpush
