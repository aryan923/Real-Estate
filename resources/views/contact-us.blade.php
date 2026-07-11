@extends('layouts.master')

@section('title', 'Contact Us')

@section('content')
    <header class="relative isolate min-h-[500px] bg-[#08192f] z-30" data-sticky-trigger>
        @include('partials.layout.topbar')

        <div class="section-shell relative z-20 py-4 sm:py-6">
            @include('partials.layout.navbar')
        </div>

        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=2200&q=80" alt="Real estate office building" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(10,25,45,0.82)_0%,rgba(8,31,58,0.76)_45%,rgba(7,31,57,0.72)_100%)]"></div>

        <div class="section-shell relative z-10 flex min-h-[360px] flex-col items-center justify-center text-center sm:min-h-[400px]">
            <h1 class="heading-display text-[2.6rem] font-extrabold text-white sm:text-[3.7rem]">Contact Us</h1>
            <p class="mt-3 max-w-2xl text-[1rem] font-medium text-white/90 sm:text-[1.15rem]">Connect with our real estate specialists for buying, selling, rentals, and investments.</p>
        </div>
    </header>

    <section class="bg-[#f3f5f7] py-4">
        <div class="section-shell">
            <div class="flex flex-wrap items-center gap-2 text-[13px] font-medium text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-[#1e73be]">Home</a>
                <span>›</span>
                <span class="text-[#1e73be]">Contact Us</span>
            </div>
        </div>
    </section>

    <section class="bg-[#f3f5f7] py-14 sm:py-16">
        <div class="section-shell">
            <div class="mx-auto max-w-5xl text-center">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-copy mx-auto mt-4 max-w-3xl">
                    We are here to answer your questions and help you make confident real estate decisions.
                    Send us a message and our team will get back to you quickly.
                </p>
            </div>

            <div class="mx-auto mt-10 grid max-w-6xl gap-5 md:grid-cols-3">
                <article class="rounded bg-white p-6 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="mx-auto inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#e9f3ff] text-[#1e73be]">☎</div>
                    <h3 class="property-card-title mt-4">Call Us</h3>
                    <p class="property-card-copy mt-3">+1 408 167 1234</p>
                    <p class="property-card-copy mt-1">+1 800 555 6789</p>
                </article>

                <article class="rounded bg-white p-6 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="mx-auto inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#e9f3ff] text-[#1e73be]">✉</div>
                    <h3 class="property-card-title mt-4">Email</h3>
                    <p class="property-card-copy mt-3">contact@mail.com</p>
                    <p class="property-card-copy mt-1">support@realscape.com</p>
                </article>

                <article class="rounded bg-white p-6 text-center shadow-sm ring-1 ring-slate-200">
                    <div class="mx-auto inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#e9f3ff] text-[#1e73be]">⌂</div>
                    <h3 class="property-card-title mt-4">Office Address</h3>
                    <p class="property-card-copy mt-3">1234 Beacon Street, Suite 101</p>
                    <p class="property-card-copy mt-1">Boston, MA 02118</p>
                </article>
            </div>

            <div class="mx-auto mt-10 grid max-w-6xl overflow-hidden rounded bg-white shadow-sm ring-1 ring-slate-200 lg:grid-cols-[1.1fr_1fr]">
                <div class="min-h-[460px]">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80" alt="Client consultation in office" class="h-full w-full object-cover">
                </div>

                <div class="p-6 sm:p-8">
                    <h3 class="section-title">Send Us A Message</h3>
                    <p class="section-copy mt-3">Tell us what you are looking for and we will match you with the right advisor.</p>

                    <form id="contact-form" class="mt-6 space-y-3" novalidate>
                        @csrf
                        <input type="hidden" name="type" value="contact">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <input type="text" name="first_name" placeholder="First Name" required class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                <span class="error-msg text-xs text-red-500 hidden mt-1">First name is required.</span>
                            </div>
                            <div>
                                <input type="text" name="last_name" placeholder="Last Name" required class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                <span class="error-msg text-xs text-red-500 hidden mt-1">Last name is required.</span>
                            </div>
                            <div>
                                <input type="email" name="email" placeholder="Email" required class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter a valid email address.</span>
                            </div>
                            <div>
                                <input type="tel" name="phone" placeholder="Phone" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                                <span class="error-msg text-xs text-red-500 hidden mt-1">Please enter a valid phone number.</span>
                            </div>
                        </div>

                        <select name="enq_for" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]">
                            <option value="">Select Inquiry Type</option>
                            <option value="sale">Sale</option>
                            <option value="rent">Rent</option>
                            <option value="lease">Lease</option>
                        </select>

                        <textarea name="message" rows="5" placeholder="Message" class="w-full rounded border border-slate-200 bg-[#f7f8fa] px-3 py-2.5 text-[14px] text-slate-700 outline-none focus:border-[#1e73be]"></textarea>

                        <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded bg-[#1e73be] px-7 text-[14px] font-semibold text-white hover:bg-[#0f6fcb] transition flex items-center justify-center gap-2">
                            <span class="submit-text">Send Message</span>
                            <svg class="spinner hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="mx-auto mt-10 max-w-6xl overflow-hidden rounded bg-white shadow-sm ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-6 py-4 sm:px-7">
                    <h3 class="section-title">Find Us On The Map</h3>
                    <p class="section-copy mt-2">Visit our office at 1234 Beacon Street, Suite 101, Boston, MA 02118.</p>
                </div>
                <iframe
                    title="RealScape Office Location"
                    class="h-[360px] w-full border-0 sm:h-[420px]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=1234+Beacon+Street,+Boston,+MA+02118&output=embed">
                </iframe>
            </div>
        </div>
    </section>

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

    $('#contact-form').on('submit', function(e) {
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

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please correct the errors in the form.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e73be'
                    });

                    // Map backend errors back to fields
                    Object.keys(errors).forEach(function(field) {
                        if (field === 'name') {
                            showFieldError(firstNameInput, errors[field][0]);
                            showFieldError(lastNameInput, errors[field][0]);
                        } else {
                            const inputEl = $form.find(`[name="${field}"]`);
                            if (inputEl.length) {
                                showFieldError(inputEl, errors[field][0]);
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e73be'
                    });
                }
            }
        });
    });
});
</script>
@endpush
