@extends('layouts.master')

@section('title', 'List Your Property')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')

<style>
/* ─── Wizard Container ─────────────────────────────────────────── */
.wizard-shell {
    background: #f8fafc;
    min-height: 100vh;
}

/* ─── Step Bubble ──────────────────────────────────────────────── */
.step-bubble {
    width: 52px; height: 52px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 800;
    transition: all .3s ease;
    position: relative; z-index: 1;
    flex-shrink: 0;
}
.step-bubble.pending {
    background: #ffffff;
    color: #94a3b8;
    border: 2px solid #e2e8f0;
}
.step-bubble.active {
    background: #1e73be;
    color: #fff;
    border: 2px solid #1e73be;
    box-shadow: 0 0 0 5px rgba(30, 115, 190, 0.15);
}
.step-bubble.done {
    background: #10b981;
    color: #fff;
    border: 2px solid #10b981;
    box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.15);
}

/* ─── Form Fields ──────────────────────────────────────────────── */
.wiz-input {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    padding: 10px 14px;
    font-size: 14px;
    color: #0f172a;
    outline: none;
    transition: all .2s ease;
}
.wiz-input:focus {
    border-color: #1e73be;
    box-shadow: 0 0 0 3px rgba(30, 115, 190, 0.1);
}
.wiz-input.err {
    border-color: #ef4444;
    background: #fff8f8;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}
.wiz-input.ok {
    border-color: #10b981;
}

.wiz-label {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #475569;
}
.wiz-label span.req { color: #ef4444; }

.wiz-err {
    display: none;
    margin-top: 5px;
    font-size: 12px;
    color: #ef4444;
    font-weight: 500;
    align-items: center;
    gap: 4px;
}
.wiz-err.show { display: flex; }

/* ─── Listing Type Cards ───────────────────────────────────────── */
.listing-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px 16px;
    text-align: center;
    cursor: pointer;
    transition: all .2s ease;
    background: #ffffff;
    position: relative;
}
.listing-card:hover {
    border-color: #1e73be;
    background: #f8fafc;
}
.listing-card.selected {
    border-color: #1e73be;
    background: #f0f7ff;
    box-shadow: 0 0 0 3px rgba(30, 115, 190, 0.1);
}
.listing-card.selected .listing-icon {
    transform: scale(1.05);
}
.listing-icon {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    transition: transform .2s ease;
}

/* ─── Purpose Radio Chips ──────────────────────────────────────── */
.purpose-chip {
    flex: 1;
    border: 2px solid #e2eaf4;
    border-radius: 10px;
    padding: 12px 8px;
    text-align: center;
    cursor: pointer;
    transition: all .22s;
    background: #f8fafc;
    font-weight: 700;
    font-size: 13px;
    color: #6882a0;
}
.purpose-chip:hover {
    border-color: #93c5fd;
    background: #f0f7ff;
}
.purpose-chip.selected {
    border-color: #1e73be;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: #1e40af;
    box-shadow: 0 2px 10px rgba(30,115,190,.15);
}

/* ─── Counter Input ────────────────────────────────────────────── */
.counter-box {
    border: 2px solid #e2eaf4;
    border-radius: 10px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    overflow: hidden;
}
.counter-btn {
    width: 40px; height: 46px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 18px;
    font-weight: 700;
    color: #3b82f6;
    transition: background .2s;
    background: transparent;
    border: none;
    flex-shrink: 0;
}
.counter-btn:hover { background: #eff6ff; }
.counter-val {
    flex: 1;
    text-align: center;
    font-size: 16px;
    font-weight: 800;
    color: #1f2a44;
    border: none;
    background: transparent;
    outline: none;
    min-width: 0;
}

/* ─── File Upload Zone ─────────────────────────────────────────── */
.file-zone {
    border: 2px dashed #c7d9ed;
    border-radius: 12px;
    background: #f8fafc;
    padding: 24px 16px;
    text-align: center;
    cursor: pointer;
    transition: all .25s;
    position: relative;
}
.file-zone:hover, .file-zone.drag {
    border-color: #3b82f6;
    background: #f0f7ff;
}
.file-zone input[type="file"] {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    opacity: 0; cursor: pointer;
}
.file-zone.has-file {
    border-color: #22c55e;
    background: #f0fff7;
}

/* ─── Category Checkbox Grid ───────────────────────────────────── */
.cat-chip {
    border: 1.5px solid #e2eaf4;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 12px;
    font-weight: 600;
    color: #6882a0;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cat-chip input[type="checkbox"] { display: none; }
.cat-chip.checked {
    border-color: #3b82f6;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: #1e40af;
}
.cat-chip .dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    flex-shrink: 0;
    transition: all .2s;
}
.cat-chip.checked .dot {
    background: #3b82f6;
    border-color: #3b82f6;
}

/* ─── Feature checkboxes ───────────────────────────────────────── */
.feat-item {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 600; color: #4b6180;
    cursor: pointer; padding: 8px 10px;
    border-radius: 8px;
    transition: background .15s;
}
.feat-item:hover { background: #f0f7ff; }
.feat-item input[type="checkbox"] {
    width: 16px; height: 16px; accent-color: #3b82f6; cursor: pointer; flex-shrink: 0;
}

/* ─── Review Cards ─────────────────────────────────────────────── */
.review-card {
    border: 1.5px solid #e2eaf4;
    border-radius: 14px;
    background: #f8fafc;
    overflow: hidden;
}
.review-card-head {
    background: linear-gradient(90deg, #eff6ff 0%, #dbeafe 100%);
    padding: 10px 16px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #1e4799;
    border-bottom: 1px solid #dbeafe;
    display: flex; align-items: center; gap: 6px;
}
.review-row {
    display: grid; grid-template-columns: 130px 1fr;
    gap: 4px 12px;
    padding: 10px 16px;
    border-bottom: 1px solid #eef3f9;
    align-items: start;
}
.review-row:last-child { border-bottom: none; }
.review-lbl { font-size: 11px; font-weight: 700; color: #8faabf; text-transform: uppercase; letter-spacing: .04em; }
.review-val { font-size: 13px; font-weight: 700; color: #1f2a44; word-break: break-word; }
.review-edit-btn {
    font-size: 11px; font-weight: 700; color: #3b82f6; cursor: pointer;
    padding: 4px 10px; border-radius: 6px; border: 1.5px solid #93c5fd;
    transition: all .2s; background: transparent; white-space: nowrap; flex-shrink: 0;
}
.review-edit-btn:hover { background: #eff6ff; }

/* ─── Nav Buttons ──────────────────────────────────────────────── */
.wiz-btn-next {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px 32px;
    border-radius: 10px;
    background: linear-gradient(135deg, #1e73be 0%, #3b82f6 100%);
    color: #fff; font-weight: 800; font-size: 14px;
    box-shadow: 0 4px 16px rgba(30,115,190,.30);
    transition: all .25s;
    border: none; cursor: pointer;
}
.wiz-btn-next:hover {
    transform: translateY(-1.5px);
    box-shadow: 0 8px 22px rgba(30,115,190,.38);
}
.wiz-btn-next:disabled {
    opacity: .6; cursor: not-allowed; transform: none; box-shadow: none;
}
.wiz-btn-back {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 11px 24px;
    border-radius: 10px;
    background: #fff; color: #4b6180; font-weight: 700; font-size: 14px;
    border: 2px solid #dde8f4;
    transition: all .2s; cursor: pointer;
}
.wiz-btn-back:hover { background: #f0f7ff; border-color: #93c5fd; color: #1e73be; }

/* ─── Success State ────────────────────────────────────────────── */
.success-wave { animation: successPop .6s cubic-bezier(.17,.89,.32,1.49) both; }
@keyframes successPop {
    0% { transform: scale(0.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

/* ─── Micro-anim for steps ─────────────────────────────────────── */
[x-cloak] { display: none !important; }
.step-fade-enter { animation: stepIn .3s ease both; }
@keyframes stepIn {
    0% { opacity: 0; transform: translateX(18px); }
    100% { opacity: 1; transform: translateX(0); }
}

/* ─── Image preview grid ───────────────────────────────────────── */
.img-thumb {
    width: 72px; height: 72px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e2eaf4;
}
</style>

{{-- ─── HERO HEADER (Same style as Properties Hero Map section) ─── --}}
<header class="relative isolate overflow-visible bg-[#0f355e] z-30">
    @include('partials.layout.topbar')

    <div class="section-shell relative z-20 py-4 sm:py-6">
        @include('partials.layout.navbar')
    </div>
</header>

<section class="relative h-[220px] sm:h-[280px] overflow-hidden bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80');" data-sticky-trigger>
    <div class="absolute inset-0 bg-[#0f2743]/70"></div>
    <div class="section-shell relative z-10 flex h-full flex-col justify-center py-10 sm:py-14 text-white">
        <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">List Your Property</h1>
        <p class="mt-2 text-sm font-medium text-white/80">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <span class="mx-2">/</span>
            <span>List Your Property</span>
        </p>
    </div>
</section>

{{-- ─── WIZARD SECTION ───────────────────────────────────────────── --}}
<div class="wizard-shell py-12 sm:py-16" x-data="propertyWizard()" x-cloak>
    <div class="section-shell">
        <div class="mx-auto max-w-3xl">

            {{-- ─── STEP INDICATOR ──────────────────────────────── --}}
            <div class="relative select-none w-full" style="margin-bottom: 2.25rem;">
                <div class="relative flex justify-between items-start w-full">
                    <template x-for="(s, i) in steps" :key="i">
                        <div class="flex-1 flex flex-col items-center relative">
                            {{-- Connector Line (stretches from this bubble center to next bubble center with gaps) --}}
                            <div x-show="i < steps.length - 1" 
                                 class="absolute" 
                                 style="top: 26px; left: calc(50% + 26px + 12px); right: calc(-50% + 26px + 12px); height: 3.5px; transform: translateY(-50%); transition: all 0.3s ease; z-index: 0;"
                                 :style="{ backgroundColor: step > i+1 ? '#10b981' : '#cbd5e1' }">
                            </div>

                            {{-- Bubble --}}
                            <div class="step-bubble"
                                 :class="step === i+1 ? 'active' : (step > i+1 ? 'done' : 'pending')">
                                <template x-if="step > i+1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </template>
                                <template x-if="step <= i+1">
                                    <span x-text="i+1"></span>
                                </template>
                            </div>

                            {{-- Label --}}
                            <div class="mt-3 text-center px-1">
                                <span class="text-[12px] sm:text-[13px] font-bold tracking-tight transition-all duration-300 block"
                                     :class="step === i+1 ? 'text-[#1e73be]' : (step > i+1 ? 'text-[#10b981]' : 'text-slate-400')"
                                     x-text="s.label"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ─── WIZARD FORM CARD ────────────────────────────── --}}
            <div class="rounded-2xl bg-white shadow-[0_12px_40px_rgba(0,0,0,0.06)] ring-1 ring-slate-100 overflow-hidden" style="margin-top: 2.5rem;">

                {{-- ─── STEP 1: SUBMITTER INFO ──────────────────── --}}
                <div x-show="step === 1" class="step-fade-enter p-6 sm:p-10">
                    <div class="mb-8 flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-[#1f2a44] sm:text-xl">Your Contact Details</h2>
                            <p class="mt-1 text-[13px] text-slate-400">We'll reach out for property verification before listing goes live.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        {{-- Name & Email --}}
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="wiz-label">Full Name <span class="req">*</span></label>
                                <input type="text" id="f-name" x-model.lazy="formData.submitter_name"
                                    @blur="validateField('submitter_name')"
                                    @input="clearErr('submitter_name')"
                                    :class="fieldErr.submitter_name ? 'wiz-input err' : (formData.submitter_name ? 'wiz-input ok' : 'wiz-input')"
                                    placeholder="e.g. Rahul Sharma">
                                <div class="wiz-err" :class="fieldErr.submitter_name ? 'show' : ''">
                                    <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span x-text="fieldErr.submitter_name"></span>
                                </div>
                            </div>
                            <div>
                                <label class="wiz-label">Email Address <span class="req">*</span></label>
                                <input type="email" id="f-email" x-model.lazy="formData.submitter_email"
                                    @blur="validateField('submitter_email')"
                                    @input="clearErr('submitter_email')"
                                    :class="fieldErr.submitter_email ? 'wiz-input err' : (formData.submitter_email ? 'wiz-input ok' : 'wiz-input')"
                                    placeholder="e.g. rahul@email.com">
                                <div class="wiz-err" :class="fieldErr.submitter_email ? 'show' : ''">
                                    <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span x-text="fieldErr.submitter_email"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="max-w-xs">
                            <label class="wiz-label">Phone Number</label>
                            <input type="tel" x-model="formData.submitter_phone"
                                class="wiz-input" placeholder="+91 98765 43210">
                        </div>

                        {{-- Listing Type Cards --}}
                        <div>
                            <label class="wiz-label">I am a <span class="req">*</span></label>
                            <div class="grid grid-cols-3 gap-3 mt-1">
                                <div @click="formData.listing_type = 'owner'; clearErr('listing_type')"
                                     :class="formData.listing_type === 'owner' ? 'listing-card selected' : 'listing-card'">
                                    <span class="listing-icon">🏠</span>
                                    <div class="text-sm font-bold text-slate-700">Owner</div>
                                    <div class="mt-1 text-[11px] text-slate-400">Direct owner listing</div>
                                </div>
                                <div @click="formData.listing_type = 'agent'; clearErr('listing_type')"
                                     :class="formData.listing_type === 'agent' ? 'listing-card selected' : 'listing-card'">
                                    <span class="listing-icon">🤝</span>
                                    <div class="text-sm font-bold text-slate-700">Agent</div>
                                    <div class="mt-1 text-[11px] text-slate-400">Licensed real estate agent</div>
                                </div>
                                <div @click="formData.listing_type = 'builder'; clearErr('listing_type')"
                                     :class="formData.listing_type === 'builder' ? 'listing-card selected' : 'listing-card'">
                                    <span class="listing-icon">🏗️</span>
                                    <div class="text-sm font-bold text-slate-700">Builder</div>
                                    <div class="mt-1 text-[11px] text-slate-400">Developer / builder</div>
                                </div>
                            </div>
                            <div class="wiz-err mt-2" :class="fieldErr.listing_type ? 'show' : ''">
                                <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span x-text="fieldErr.listing_type"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── STEP 2: PROPERTY OVERVIEW ───────────────── --}}
                <div x-show="step === 2" class="step-fade-enter p-6 sm:p-10">
                    <div class="mb-8 flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-[#1f2a44] sm:text-xl">Property Overview</h2>
                            <p class="mt-1 text-[13px] text-slate-400">Provide the basic details — title, purpose, price and size.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        {{-- Title --}}
                        <div>
                            <label class="wiz-label">Property Title <span class="req">*</span></label>
                            <input type="text" x-model.lazy="formData.title"
                                @blur="validateField('title')" @input="clearErr('title')"
                                :class="fieldErr.title ? 'wiz-input err' : (formData.title ? 'wiz-input ok' : 'wiz-input')"
                                placeholder="e.g. Luxurious 3 BHK Apartment in Banjara Hills">
                            <div class="wiz-err" :class="fieldErr.title ? 'show' : ''">
                                <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span x-text="fieldErr.title"></span>
                            </div>
                        </div>

                        {{-- Purpose Chips --}}
                        <div>
                            <label class="wiz-label">For Sale / Rent / Lease <span class="req">*</span></label>
                            <div class="mt-1 flex gap-3">
                                <div @click="formData.property_purpose = 'sale'; clearErr('property_purpose')"
                                     :class="formData.property_purpose === 'sale' ? 'purpose-chip selected' : 'purpose-chip'">
                                    🏷️ For Sale
                                </div>
                                <div @click="formData.property_purpose = 'rent'; clearErr('property_purpose')"
                                     :class="formData.property_purpose === 'rent' ? 'purpose-chip selected' : 'purpose-chip'">
                                    🔑 For Rent
                                </div>
                                <div @click="formData.property_purpose = 'lease'; clearErr('property_purpose')"
                                     :class="formData.property_purpose === 'lease' ? 'purpose-chip selected' : 'purpose-chip'">
                                    📋 For Lease
                                </div>
                            </div>
                            <div class="wiz-err mt-2" :class="fieldErr.property_purpose ? 'show' : ''">
                                <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span x-text="fieldErr.property_purpose"></span>
                            </div>
                        </div>

                        {{-- Price & Price type --}}
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="wiz-label">Listing Price <span class="req">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">₹</span>
                                    <input type="number" x-model.lazy="formData.price"
                                        @blur="validateField('price')" @input="clearErr('price')"
                                        :class="fieldErr.price ? 'wiz-input err' : (formData.price ? 'wiz-input ok' : 'wiz-input')"
                                        class="pl-8!" placeholder="e.g. 4500000"
                                        style="padding-left: 2rem;">
                                </div>
                                <div class="wiz-err" :class="fieldErr.price ? 'show' : ''">
                                    <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span x-text="fieldErr.price"></span>
                                </div>
                            </div>
                            <div>
                                <label class="wiz-label">Price Suffix</label>
                                <input type="text" x-model="formData.price_type" class="wiz-input" placeholder="e.g. / Month, Total, per sq.ft.">
                            </div>
                        </div>

                        {{-- Size --}}
                        <div class="max-w-xs">
                            <label class="wiz-label">Area / Size <span class="req">*</span></label>
                            <div class="relative">
                                <input type="number" x-model.lazy="formData.size"
                                    @blur="validateField('size')" @input="clearErr('size')"
                                    :class="fieldErr.size ? 'wiz-input err' : (formData.size ? 'wiz-input ok' : 'wiz-input')"
                                    placeholder="e.g. 1800">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-bold text-slate-400">Sq. Ft.</span>
                            </div>
                            <div class="wiz-err" :class="fieldErr.size ? 'show' : ''">
                                <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span x-text="fieldErr.size"></span>
                            </div>
                        </div>

                        {{-- Categories --}}
                        @if($categories->count())
                        <div>
                            <label class="wiz-label">Property Type / Category</label>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($categories as $cat)
                                <label class="cat-chip" :class="formData.category_id.includes({{ $cat->id }}) ? 'checked' : ''">
                                    <input type="checkbox" value="{{ $cat->id }}"
                                        @change="toggleCategory({{ $cat->id }})">
                                    <span class="dot"></span>
                                    {{ $cat->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Description --}}
                        <div>
                            <label class="wiz-label">Description</label>
                            <textarea x-model="formData.description" rows="3" class="wiz-input resize-none"
                                placeholder="Describe key features, surroundings, and unique selling points..."></textarea>
                            <div class="mt-1 text-right text-[11px] text-slate-400" x-text="formData.description.length + ' characters'"></div>
                        </div>
                    </div>
                </div>

                {{-- ─── STEP 3: LOCATION & SPECS ────────────────── --}}
                <div x-show="step === 3" class="step-fade-enter p-6 sm:p-10">
                    <div class="mb-8 flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-[#1f2a44] sm:text-xl">Location & Property Specs</h2>
                            <p class="mt-1 text-[13px] text-slate-400">Where is the property located and what are its key specifications?</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        {{-- Country + State --}}
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="wiz-label">Country <span class="req">*</span></label>
                                <select x-model="formData.country_id"
                                    @change="loadStates(); clearErr('country_id')"
                                    :class="fieldErr.country_id ? 'wiz-input err' : (formData.country_id ? 'wiz-input ok' : 'wiz-input')">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <div class="wiz-err" :class="fieldErr.country_id ? 'show' : ''">
                                    <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span x-text="fieldErr.country_id"></span>
                                </div>
                            </div>
                            <div>
                                <label class="wiz-label">State / Province <span class="req">*</span></label>
                                <div class="relative">
                                    <select x-model="formData.state_id"
                                        @change="loadCities(); clearErr('state_id')"
                                        :disabled="!formData.country_id || loadingStates"
                                        :class="fieldErr.state_id ? 'wiz-input err' : (formData.state_id ? 'wiz-input ok' : 'wiz-input')">
                                        <option value="">Select State</option>
                                        <template x-for="s in states" :key="s.id">
                                            <option :value="s.id" x-text="s.name"></option>
                                        </template>
                                    </select>
                                    <div x-show="loadingStates" class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="h-4 w-4 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    </div>
                                </div>
                                <div class="wiz-err" :class="fieldErr.state_id ? 'show' : ''">
                                    <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span x-text="fieldErr.state_id"></span>
                                </div>
                            </div>
                        </div>

                        {{-- City + Locality --}}
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="wiz-label">City / District <span class="req">*</span></label>
                                <div class="relative">
                                    <select x-model="formData.city_id"
                                        :disabled="!formData.state_id || loadingCities"
                                        @change="clearErr('city_id')"
                                        :class="fieldErr.city_id ? 'wiz-input err' : (formData.city_id ? 'wiz-input ok' : 'wiz-input')">
                                        <option value="">Select City</option>
                                        <template x-for="c in cities" :key="c.id">
                                            <option :value="c.id" x-text="c.name"></option>
                                        </template>
                                    </select>
                                    <div x-show="loadingCities" class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="h-4 w-4 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    </div>
                                </div>
                                <div class="wiz-err" :class="fieldErr.city_id ? 'show' : ''">
                                    <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span x-text="fieldErr.city_id"></span>
                                </div>
                            </div>
                            <div>
                                <label class="wiz-label">Locality / Sector / Neighbourhood</label>
                                <input type="text" x-model="formData.locality" class="wiz-input" placeholder="e.g. Banjara Hills, Sector 45">
                            </div>
                        </div>

                        {{-- Address + Zip --}}
                        <div class="grid gap-5 sm:grid-cols-3">
                            <div class="sm:col-span-2">
                                <label class="wiz-label">Street Address</label>
                                <input type="text" x-model="formData.address_line" class="wiz-input" placeholder="House/Flat No., Building, Street...">
                            </div>
                            <div>
                                <label class="wiz-label">Pin / Zip Code</label>
                                <input type="text" x-model="formData.zip" class="wiz-input" placeholder="e.g. 500034">
                            </div>
                        </div>

                        {{-- Specs Counters --}}
                        <div>
                            <label class="wiz-label">Property Configuration</label>
                            <div class="mt-2 grid grid-cols-2 gap-4 sm:grid-cols-4">
                                {{-- Bedrooms --}}
                                <div>
                                    <div class="mb-2 text-[11px] font-bold text-slate-500">🛏 Bedrooms</div>
                                    <div class="counter-box">
                                        <button type="button" class="counter-btn" @click="formData.bedrooms = Math.max(0, parseInt(formData.bedrooms||0) - 1)">−</button>
                                        <input type="number" x-model="formData.bedrooms" min="0" class="counter-val">
                                        <button type="button" class="counter-btn" @click="formData.bedrooms = parseInt(formData.bedrooms||0) + 1">+</button>
                                    </div>
                                </div>
                                {{-- Bathrooms --}}
                                <div>
                                    <div class="mb-2 text-[11px] font-bold text-slate-500">🚿 Bathrooms</div>
                                    <div class="counter-box">
                                        <button type="button" class="counter-btn" @click="formData.bathrooms = Math.max(0, parseInt(formData.bathrooms||0) - 1)">−</button>
                                        <input type="number" x-model="formData.bathrooms" min="0" class="counter-val">
                                        <button type="button" class="counter-btn" @click="formData.bathrooms = parseInt(formData.bathrooms||0) + 1">+</button>
                                    </div>
                                </div>
                                {{-- Rooms --}}
                                <div>
                                    <div class="mb-2 text-[11px] font-bold text-slate-500">🚪 Total Rooms</div>
                                    <div class="counter-box">
                                        <button type="button" class="counter-btn" @click="formData.rooms = Math.max(0, parseInt(formData.rooms||0) - 1)">−</button>
                                        <input type="number" x-model="formData.rooms" min="0" class="counter-val">
                                        <button type="button" class="counter-btn" @click="formData.rooms = parseInt(formData.rooms||0) + 1">+</button>
                                    </div>
                                </div>
                                {{-- Year Built --}}
                                <div>
                                    <div class="mb-2 text-[11px] font-bold text-slate-500">📅 Year Built</div>
                                    <input type="number" x-model="formData.year_built" class="wiz-input" placeholder="{{ date('Y') }}" min="1900" :max="new Date().getFullYear()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── STEP 4: MEDIA & FEATURES ────────────────── --}}
                <div x-show="step === 4" class="step-fade-enter p-6 sm:p-10">
                    <div class="mb-8 flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-[#1f2a44] sm:text-xl">Media & Amenities</h2>
                            <p class="mt-1 text-[13px] text-slate-400">Upload photos, documents, and check available amenities for your property.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        {{-- Cover image --}}
                        <div>
                            <label class="wiz-label">Cover / Primary Image <span class="req">*</span></label>
                            <div class="file-zone" :class="{ 'has-file': files.primary_image_file, 'err': fieldErr.primary_image_file }">
                                <input type="file" accept="image/*"
                                    @change="handleCoverImage($event)"
                                    @dragover.prevent="$el.closest('.file-zone').classList.add('drag')"
                                    @dragleave.prevent="$el.closest('.file-zone').classList.remove('drag')"
                                    @drop.prevent="$el.closest('.file-zone').classList.remove('drag')">
                                <template x-if="!files.primary_image_file">
                                    <div>
                                        <svg class="mx-auto h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-sm font-bold text-slate-500">Drag & drop or <span class="text-[#1e73be]">Browse</span></p>
                                        <p class="mt-1 text-[11px] text-slate-400">JPG, PNG, WebP — max 10 MB</p>
                                    </div>
                                </template>
                                <template x-if="files.primary_image_file">
                                    <div class="flex items-center gap-4">
                                        <img :src="coverPreview" class="img-thumb" alt="Cover preview">
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-emerald-700" x-text="files.primary_image_file.name"></div>
                                            <div class="mt-1 text-[11px] text-slate-400" x-text="(files.primary_image_file.size / 1024 / 1024).toFixed(2) + ' MB'"></div>
                                            <button type="button" @click.stop="files.primary_image_file = null; coverPreview = null" class="mt-2 text-xs font-bold text-red-500 hover:text-red-700">Remove</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="wiz-err mt-2" :class="fieldErr.primary_image_file ? 'show' : ''">
                                <svg class="h-3 w-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span x-text="fieldErr.primary_image_file"></span>
                            </div>
                        </div>

                        {{-- Gallery --}}
                        <div>
                            <label class="wiz-label">Gallery Images <span class="text-slate-400 font-medium normal-case tracking-normal">(up to 10)</span></label>
                            <div class="file-zone" :class="{ 'has-file': galleryPreviews.length }">
                                <input type="file" accept="image/*" multiple @change="handleGallery($event)">
                                <template x-if="!galleryPreviews.length">
                                    <div>
                                        <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="text-sm font-bold text-slate-500">Select multiple images</p>
                                    </div>
                                </template>
                                <template x-if="galleryPreviews.length">
                                    <div class="flex flex-wrap gap-2 pointer-events-none">
                                        <template x-for="(src, idx) in galleryPreviews" :key="idx">
                                            <img :src="src" class="img-thumb" alt="Gallery image">
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Floor Plan + Video --}}
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="wiz-label">Floor Plan <span class="text-slate-400 font-medium normal-case tracking-normal">(PDF, max 10 MB)</span></label>
                                <div class="file-zone" :class="{ 'has-file': files.floor_plan_file }">
                                    <input type="file" accept="application/pdf" @change="files.floor_plan_file = $event.target.files[0]">
                                    <template x-if="!files.floor_plan_file">
                                        <div>
                                            <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <p class="text-xs font-bold text-slate-500">Upload Floor Plan PDF</p>
                                        </div>
                                    </template>
                                    <template x-if="files.floor_plan_file">
                                        <div class="flex items-center gap-2 text-left">
                                            <svg class="h-8 w-8 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM8 13h8v1H8v-1zm0 3h8v1H8v-1zm0-6h5v1H8v-1z"/></svg>
                                            <div>
                                                <div class="text-xs font-bold text-emerald-700 truncate max-w-[140px]" x-text="files.floor_plan_file.name"></div>
                                                <button type="button" @click.stop="files.floor_plan_file = null" class="text-xs font-bold text-red-500 hover:text-red-700">Remove</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label class="wiz-label">Walkthrough Video <span class="text-slate-400 font-medium normal-case tracking-normal">(MP4/WebM)</span></label>
                                <div class="file-zone" :class="{ 'has-file': files.video_file }">
                                    <input type="file" accept="video/*" @change="files.video_file = $event.target.files[0]">
                                    <template x-if="!files.video_file">
                                        <div>
                                            <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.888L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <p class="text-xs font-bold text-slate-500">Upload Walkthrough Video</p>
                                        </div>
                                    </template>
                                    <template x-if="files.video_file">
                                        <div class="text-left">
                                            <div class="text-xs font-bold text-emerald-700 truncate max-w-[160px]" x-text="files.video_file.name"></div>
                                            <div class="mt-1 text-[11px] text-slate-400" x-text="(files.video_file.size / 1024 / 1024).toFixed(1) + ' MB'"></div>
                                            <button type="button" @click.stop="files.video_file = null" class="text-xs font-bold text-red-500 hover:text-red-700">Remove</button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Features (Styled in nested cards mimicking the requested SS layout) --}}
                        @if($features->count())
                        <div>
                            <label class="wiz-label mb-3">Amenities & Features</label>
                            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                    @foreach ($features->whereNull('parent_id') as $feat)
                                        <div class="rounded-xl border border-slate-200 bg-white p-4 transition hover:border-[#1e73be]">
                                            <label class="flex cursor-pointer items-center gap-3 text-sm font-semibold text-slate-900">
                                                <input type="checkbox" value="{{ $feat->id }}" x-model="formData.feature_id"
                                                       class="h-4 w-4 rounded border-gray-300 accent-[#1e73be]" />
                                                {{ $feat->name }}
                                            </label>
                                            @if ($feat->children->count())
                                                <div class="mt-3 space-y-2 pl-7 border-l border-slate-100">
                                                    @foreach ($feat->children as $child)
                                                        <label class="flex cursor-pointer items-center gap-3 text-xs text-slate-500 hover:text-slate-900">
                                                            <input type="checkbox" value="{{ $child->id }}" x-model="formData.feature_id"
                                                                   class="h-3.5 w-3.5 rounded border-gray-300 accent-[#1e73be]" />
                                                            {{ $child->name }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ─── STEP 5: REVIEW & SUBMIT ─────────────────── --}}
                <div x-show="step === 5" class="step-fade-enter p-6 sm:p-10">
                    <div class="mb-8 flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-[#1f2a44] sm:text-xl">Review & Confirm</h2>
                            <p class="mt-1 text-[13px] text-slate-400">Review all details carefully before submitting. Click any "Edit" button to go back and make changes.</p>
                        </div>
                    </div>

                    {{-- Info banner --}}
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <p class="text-[12.5px] font-semibold text-amber-700">Your listing will be submitted for admin review. It will not be publicly visible until approved.</p>
                    </div>

                    <div class="space-y-4">
                        {{-- Submitter --}}
                        <div class="review-card">
                            <div class="review-card-head">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                                Submitter Info
                                <button type="button" @click="step = 1" class="review-edit-btn ml-auto">Edit</button>
                            </div>
                            <div class="review-row"><span class="review-lbl">Name</span><span class="review-val" x-text="formData.submitter_name || '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Email</span><span class="review-val" x-text="formData.submitter_email || '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Phone</span><span class="review-val" x-text="formData.submitter_phone || '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Listing As</span><span class="review-val capitalize" x-text="formData.listing_type || '—'"></span></div>
                        </div>

                        {{-- Property --}}
                        <div class="review-card">
                            <div class="review-card-head">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                Property Details
                                <button type="button" @click="step = 2" class="review-edit-btn ml-auto">Edit</button>
                            </div>
                            <div class="review-row"><span class="review-lbl">Title</span><span class="review-val" x-text="formData.title || '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Purpose</span><span class="review-val capitalize" x-text="formData.property_purpose || '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Price</span><span class="review-val" x-text="formData.price ? '₹ ' + Number(formData.price).toLocaleString('en-IN') + ' ' + (formData.price_type || '') : '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Area</span><span class="review-val" x-text="formData.size ? formData.size + ' Sq. Ft.' : '—'"></span></div>
                        </div>

                        {{-- Location --}}
                        <div class="review-card">
                            <div class="review-card-head">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Location
                                <button type="button" @click="step = 3" class="review-edit-btn ml-auto">Edit</button>
                            </div>
                            <div class="review-row"><span class="review-lbl">Address</span><span class="review-val" x-text="formData.address_line || '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Locality</span><span class="review-val" x-text="formData.locality || '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Zip Code</span><span class="review-val" x-text="formData.zip || '—'"></span></div>
                            <div class="review-row"><span class="review-lbl">Config</span>
                                <span class="review-val">
                                    <span x-show="formData.bedrooms" x-text="formData.bedrooms + ' Bed  '"></span>
                                    <span x-show="formData.bathrooms" x-text="formData.bathrooms + ' Bath  '"></span>
                                    <span x-show="formData.year_built" x-text="'Built ' + formData.year_built"></span>
                                    <span x-show="!formData.bedrooms && !formData.bathrooms && !formData.year_built">—</span>
                                </span>
                            </div>
                        </div>

                        {{-- Media --}}
                        <div class="review-card">
                            <div class="review-card-head">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                                Media
                                <button type="button" @click="step = 4" class="review-edit-btn ml-auto">Edit</button>
                            </div>
                            <div class="review-row"><span class="review-lbl">Cover Image</span><span class="review-val" x-text="files.primary_image_file ? files.primary_image_file.name : 'Not selected'"></span></div>
                            <div class="review-row"><span class="review-lbl">Gallery</span><span class="review-val" x-text="files.gallery_images ? files.gallery_images.length + ' photo(s)' : 'None'"></span></div>
                            <div class="review-row"><span class="review-lbl">Floor Plan</span><span class="review-val" x-text="files.floor_plan_file ? files.floor_plan_file.name : 'Not uploaded'"></span></div>
                            <div class="review-row"><span class="review-lbl">Video</span><span class="review-val" x-text="files.video_file ? files.video_file.name : 'Not uploaded'"></span></div>
                        </div>
                    </div>
                </div>

                {{-- ─── NAV BUTTONS ─────────────────────────────── --}}
                <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-6 py-5 sm:px-10">
                    <button type="button" @click="prevStep()" x-show="step > 1" class="wiz-btn-back">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </button>
                    <div x-show="step === 1"></div>

                    <div class="flex items-center gap-3">
                        <span class="hidden text-[12px] font-semibold text-slate-400 sm:block" x-text="'Step ' + step + ' / ' + steps.length"></span>
                        <button type="button" @click="nextStep()" x-show="step < 5" class="wiz-btn-next">
                            Continue
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="button" @click="submitForm()" x-show="step === 5" :disabled="loading" class="wiz-btn-next">
                            <template x-if="!loading">
                                <span class="flex items-center gap-2">
                                    Submit Listing
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    Submitting…
                                </span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.layout.footer')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function propertyWizard() {
    return {
        step: 1,
        loading: false,
        loadingStates: false,
        loadingCities: false,
        states: [],
        cities: [],
        coverPreview: null,
        galleryPreviews: [],
        steps: [
            { label: 'Your Info' },
            { label: 'Overview' },
            { label: 'Location' },
            { label: 'Media' },
            { label: 'Review' },
        ],
        formData: {
            submitter_name: '',
            submitter_email: '',
            submitter_phone: '',
            listing_type: '',
            title: '',
            property_purpose: '',
            category_id: [],
            price: '',
            price_type: '',
            size: '',
            description: '',
            country_id: '',
            state_id: '',
            city_id: '',
            locality: '',
            address_line: '',
            zip: '',
            bedrooms: 0,
            bathrooms: 0,
            rooms: 0,
            year_built: '',
            feature_id: []
        },
        files: {
            primary_image_file: null,
            gallery_images: null,
            floor_plan_file: null,
            video_file: null
        },
        fieldErr: {
            submitter_name: '', submitter_email: '', listing_type: '',
            title: '', property_purpose: '', price: '', size: '',
            country_id: '', state_id: '', city_id: '',
            primary_image_file: ''
        },

        // ── Helpers ──────────────────────────────────────────────────
        clearErr(field) {
            this.fieldErr[field] = '';
        },

        validateField(field) {
            const emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            switch(field) {
                case 'submitter_name':
                    this.fieldErr.submitter_name = this.formData.submitter_name.trim() ? '' : 'Full name is required.';
                    break;
                case 'submitter_email':
                    if(!this.formData.submitter_email.trim()) this.fieldErr.submitter_email = 'Email is required.';
                    else if(!emailReg.test(this.formData.submitter_email)) this.fieldErr.submitter_email = 'Please enter a valid email address.';
                    else this.fieldErr.submitter_email = '';
                    break;
                case 'listing_type':
                    this.fieldErr.listing_type = this.formData.listing_type ? '' : 'Please select your listing role.';
                    break;
                case 'title':
                    this.fieldErr.title = this.formData.title.trim() ? '' : 'Property title is required.';
                    break;
                case 'property_purpose':
                    this.fieldErr.property_purpose = this.formData.property_purpose ? '' : 'Please select a purpose.';
                    break;
                case 'price':
                    if(!String(this.formData.price).trim()) this.fieldErr.price = 'Price is required.';
                    else if(isNaN(this.formData.price)) this.fieldErr.price = 'Enter a valid numeric price.';
                    else this.fieldErr.price = '';
                    break;
                case 'size':
                    if(!String(this.formData.size).trim()) this.fieldErr.size = 'Area/size is required.';
                    else if(isNaN(this.formData.size)) this.fieldErr.size = 'Enter a valid numeric size.';
                    else this.fieldErr.size = '';
                    break;
                case 'country_id':
                    this.fieldErr.country_id = this.formData.country_id ? '' : 'Please select a country.';
                    break;
                case 'state_id':
                    this.fieldErr.state_id = this.formData.state_id ? '' : 'Please select a state.';
                    break;
                case 'city_id':
                    this.fieldErr.city_id = this.formData.city_id ? '' : 'Please select a city.';
                    break;
                case 'primary_image_file':
                    this.fieldErr.primary_image_file = this.files.primary_image_file ? '' : 'A cover image is required.';
                    break;
            }
        },

        toggleCategory(id) {
            const idx = this.formData.category_id.indexOf(id);
            if (idx === -1) this.formData.category_id.push(id);
            else this.formData.category_id.splice(idx, 1);
        },

        handleCoverImage(e) {
            const file = e.target.files[0];
            if (!file) return;
            if (file.size > 10 * 1024 * 1024) {
                this.fieldErr.primary_image_file = 'File too large. Max 10 MB.';
                return;
            }
            this.files.primary_image_file = file;
            this.fieldErr.primary_image_file = '';
            const reader = new FileReader();
            reader.onload = (ev) => this.coverPreview = ev.target.result;
            reader.readAsDataURL(file);
        },

        handleGallery(e) {
            const fileList = Array.from(e.target.files);
            this.files.gallery_images = e.target.files;
            this.galleryPreviews = [];
            fileList.slice(0, 10).forEach(file => {
                const reader = new FileReader();
                reader.onload = (ev) => this.galleryPreviews.push(ev.target.result);
                reader.readAsDataURL(file);
            });
        },

        // ── Location cascades ────────────────────────────────────────
        loadStates() {
            this.states = []; this.cities = [];
            this.formData.state_id = ''; this.formData.city_id = '';
            if (!this.formData.country_id) return;
            this.loadingStates = true;
            fetch(`/api/states/${this.formData.country_id}`)
                .then(r => r.json())
                .then(d => { this.states = d; })
                .finally(() => { this.loadingStates = false; });
        },

        loadCities() {
            this.cities = []; this.formData.city_id = '';
            if (!this.formData.state_id) return;
            this.loadingCities = true;
            fetch(`/api/cities/${this.formData.state_id}`)
                .then(r => r.json())
                .then(d => { this.cities = d; })
                .finally(() => { this.loadingCities = false; });
        },

        // ── Navigation ───────────────────────────────────────────────
        prevStep() {
            if (this.step > 1) {
                this.step--;
                this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
            }
        },

        validateCurrentStep() {
            const emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let ok = true;

            if (this.step === 1) {
                ['submitter_name', 'submitter_email', 'listing_type'].forEach(f => this.validateField(f));
                ok = !this.fieldErr.submitter_name && !this.fieldErr.submitter_email && !this.fieldErr.listing_type;
            } else if (this.step === 2) {
                ['title', 'property_purpose', 'price', 'size'].forEach(f => this.validateField(f));
                ok = !this.fieldErr.title && !this.fieldErr.property_purpose && !this.fieldErr.price && !this.fieldErr.size;
            } else if (this.step === 3) {
                ['country_id', 'state_id', 'city_id'].forEach(f => this.validateField(f));
                ok = !this.fieldErr.country_id && !this.fieldErr.state_id && !this.fieldErr.city_id;
            } else if (this.step === 4) {
                this.validateField('primary_image_file');
                ok = !this.fieldErr.primary_image_file;
            }

            return ok;
        },

        nextStep() {
            if (!this.validateCurrentStep()) return;
            this.step++;
            this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
        },

        // ── Submission ───────────────────────────────────────────────
        submitForm() {
            this.loading = true;
            const payload = new FormData();

            Object.entries(this.formData).forEach(([key, val]) => {
                if (Array.isArray(val)) {
                    val.forEach(v => payload.append(`${key}[]`, v));
                } else {
                    payload.append(key, val ?? '');
                }
            });

            if (this.files.primary_image_file) payload.append('primary_image_file', this.files.primary_image_file);
            if (this.files.floor_plan_file)    payload.append('floor_plan_file', this.files.floor_plan_file);
            if (this.files.video_file)         payload.append('video_file', this.files.video_file);
            if (this.files.gallery_images) {
                for (let i = 0; i < this.files.gallery_images.length; i++) {
                    payload.append('gallery_images[]', this.files.gallery_images[i]);
                }
            }

            fetch("{{ route('list-property.store') }}", {
                method: 'POST',
                body: payload,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(res => {
                if (!res.ok && res.status === 422) return res.json().then(d => { throw { validation: true, data: d }; });
                return res.json();
            })
            .then(data => {
                this.loading = false;
                if (data.success) {
                    Swal.fire({
                        title: '🎉 Submitted!',
                        html: `<p class="text-gray-600">${data.message}</p>`,
                        icon: 'success',
                        confirmButtonText: 'Back to Home',
                        confirmButtonColor: '#1e73be',
                        showClass: { popup: 'animate__animated animate__fadeInDown' }
                    }).then(() => { window.location.href = "{{ route('home') }}"; });
                } else {
                    Swal.fire({ title: 'Oops!', text: data.message || 'Something went wrong.', icon: 'error', confirmButtonColor: '#ef4444' });
                }
            })
            .catch(err => {
                this.loading = false;
                if (err.validation && err.data?.errors) {
                    const msgs = Object.values(err.data.errors).flat().join('<br>');
                    Swal.fire({ title: 'Validation Error', html: msgs, icon: 'warning', confirmButtonColor: '#f59e0b' });
                } else {
                    Swal.fire({ title: 'Error!', text: 'An unexpected error occurred. Please try again.', icon: 'error', confirmButtonColor: '#ef4444' });
                }
            });
        }
    };
}
</script>
@endpush
