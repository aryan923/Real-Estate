@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Property' : 'Add Property')
@section('page', 'properties')

@section('content')
    @include('admin.partials.admin-header')

    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <div class="grid grid-cols-1 gap-4 md:gap-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $mode === 'edit' ? 'Edit Property' : 'Add Property' }}</h1>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $mode === 'edit' ? 'Update the property information below.' : 'Create a new property record.' }}
                            </p>
                        </div>
                        <a href="{{ route('admin.page', ['page' => 'properties']) }}"
                            class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Back
                            to list</a>
                    </div>
                </div>

                <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <form method="POST" class="property-form"
                        action="{{ $mode === 'edit' ? route('admin.properties.update', $property) : route('admin.properties.store') }}">
                        @csrf
                        @if ($mode === 'edit')
                            @method('PUT')
                        @endif
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">
                                    Property Title
                                </label>
                                <input type="text" name="title" value="{{ old('title', $property['title'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">
                                    State
                                </label>
                                <select id="state_id" name="state_id"
                                    class="state-select w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10"
                                    {{ $states->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">Select state</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ (int) old('state_id', $property->state_id ?? '') === $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Status</label>
                                <select name="status"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10">
                                    <option value="active"
                                        {{ old('status', $property['status'] ?? '') === 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="pending"
                                        {{ old('status', $property['status'] ?? '') === 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="sold"
                                        {{ old('status', $property['status'] ?? '') === 'sold' ? 'selected' : '' }}>Sold
                                    </option>
                                    <option value="draft"
                                        {{ old('status', $property['status'] ?? '') === 'draft' ? 'selected' : '' }}>
                                        Draft</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Price</label>
                                <input type="text" name="price" value="{{ old('price', $property['price'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Price
                                    type</label>
                                <select name="price_type"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10">
                                    <option value="Sale"
                                        {{ old('price_type', $property['price_type'] ?? '') === 'Sale' ? 'selected' : '' }}>
                                        Sale</option>
                                    <option value="Rent"
                                        {{ old('price_type', $property['price_type'] ?? '') === 'Rent' ? 'selected' : '' }}>
                                        Rent</option>
                                    <option value="Lease"
                                        {{ old('price_type', $property['price_type'] ?? '') === 'Lease' ? 'selected' : '' }}>
                                        Lease</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Available
                                    from</label>
                                <input type="date" name="available_from"
                                    value="{{ old('available_from', $property['available_from'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label
                                    class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Bedrooms</label>
                                <input type="number" name="bedrooms" min="0"
                                    value="{{ old('bedrooms', $property['bedrooms'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                            <div>
                                <label
                                    class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Bathrooms</label>
                                <input type="number" name="bathrooms" min="0"
                                    value="{{ old('bathrooms', $property['bathrooms'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Size
                                    (ft²)</label>
                                <input type="text" name="size" value="{{ old('size', $property['size'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                            <div>
                                @php
                                    $selectedCategories = old('category_id', []);
                                    if (
                                        $mode === 'edit' &&
                                        empty(old('category_id')) &&
                                        isset($property) &&
                                        $property->categories
                                    ) {
                                        $selectedCategories = $property->categories->pluck('id')->toArray();
                                    }
                                    if (!is_array($selectedCategories)) {
                                        $selectedCategories = [$selectedCategories];
                                    }
                                @endphp
                                <label
                                    class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Category</label>
                                <div class="relative">
                                    <button type="button"
                                        class="category-dropdown-button w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-left text-sm text-gray-800 transition hover:border-brand-500 focus:border-brand-500 focus:outline-none"
                                        aria-expanded="false">
                                        <span
                                            class="category-dropdown-label">{{ count($selectedCategories) ? count($selectedCategories) . ' selected' : 'Select categories' }}</span>
                                        <span class="float-right text-slate-400">▾</span>
                                    </button>
                                    <div
                                        class="category-dropdown-panel invisible absolute left-0 top-full z-50 mt-2 w-full max-h-80 overflow-y-auto rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl transition-opacity duration-200 opacity-0 dark:border-slate-700 dark:bg-slate-900">
                                        <div
                                            class="mb-3 border-b border-slate-200 pb-3 text-sm text-slate-600 dark:border-slate-700 dark:text-slate-300">
                                            Select categories and child items</div>
                                        <div class="space-y-3">
                                            @foreach ($categories as $category)
                                                <div
                                                    class="rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-950">
                                                    <label
                                                        class="flex items-center gap-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                                        <input type="checkbox" name="category_id[]"
                                                            value="{{ $category->id }}"
                                                            class="category-parent-checkbox h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                                                            data-category-id="{{ $category->id }}"
                                                            {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }} />
                                                        <span>{{ $category->name }}</span>
                                                    </label>
                                                    @if ($category->children->count())
                                                        <div class="mt-3 space-y-2 pl-7">
                                                            @foreach ($category->children as $child)
                                                                <label
                                                                    class="flex items-center gap-3 text-sm text-slate-700 dark:text-slate-300">
                                                                    <input type="checkbox" name="category_id[]"
                                                                        value="{{ $child->id }}"
                                                                        class="category-child-checkbox h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                                                                        data-parent-id="{{ $category->id }}"
                                                                        {{ in_array($child->id, $selectedCategories) ? 'checked' : '' }} />
                                                                    <span>{{ $child->name }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mt-6">
                            <div>
                                @php
                                    $selectedFeatures = old('feature_id', []);
                                    if (
                                        $mode === 'edit' &&
                                        empty(old('feature_id')) &&
                                        isset($property) &&
                                        $property->features
                                    ) {
                                        $selectedFeatures = $property->features->pluck('id')->toArray();
                                    }
                                    if (!is_array($selectedFeatures)) {
                                        $selectedFeatures = [$selectedFeatures];
                                    }
                                @endphp
                                <div
                                    class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                    <div class="mb-3 text-sm font-semibold text-slate-900 dark:text-slate-100">Features
                                    </div>
                                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                        @foreach ($features as $feature)
                                            <div
                                                class="rounded-2xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900">
                                                <label
                                                    class="feature-parent flex items-center gap-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                                    <input type="checkbox" name="feature_id[]" value="{{ $feature->id }}"
                                                        class="feature-parent-checkbox h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                                                        data-feature-id="{{ $feature->id }}"
                                                        {{ in_array($feature->id, $selectedFeatures) ? 'checked' : '' }} />
                                                    <span>{{ $feature->name }}</span>
                                                </label>
                                                @if ($feature->children->count())
                                                    <div class="mt-3 space-y-2 pl-7">
                                                        @foreach ($feature->children as $child)
                                                            <label
                                                                class="feature-child flex items-center gap-3 text-sm text-slate-700 dark:text-slate-300">
                                                                <input type="checkbox" name="feature_id[]"
                                                                    value="{{ $child->id }}"
                                                                    class="feature-child-checkbox h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                                                                    data-parent-id="{{ $feature->id }}"
                                                                    {{ in_array($child->id, $selectedFeatures) ? 'checked' : '' }} />
                                                                <span>{{ $child->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Property
                                    code</label>
                                <input type="text" name="property_code"
                                    value="{{ old('property_code', $property['property_code'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Property
                                    purpose</label>
                                <select name="property_purpose"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10">
                                    <option value="">Select purpose</option>
                                    <option value="sale"
                                        {{ old('property_purpose', $property['property_purpose'] ?? '') === 'sale' ? 'selected' : '' }}>
                                        Sale</option>
                                    <option value="rent"
                                        {{ old('property_purpose', $property['property_purpose'] ?? '') === 'rent' ? 'selected' : '' }}>
                                        Rent</option>
                                    <option value="lease"
                                        {{ old('property_purpose', $property['property_purpose'] ?? '') === 'lease' ? 'selected' : '' }}>
                                        Lease</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Listing
                                    type</label>
                                <select name="listing_type"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10">
                                    <option value="">Select type</option>
                                    <option value="owner"
                                        {{ old('listing_type', $property['listing_type'] ?? '') === 'owner' ? 'selected' : '' }}>
                                        Owner</option>
                                    <option value="agent"
                                        {{ old('listing_type', $property['listing_type'] ?? '') === 'agent' ? 'selected' : '' }}>
                                        Agent</option>
                                    <option value="builder"
                                        {{ old('listing_type', $property['listing_type'] ?? '') === 'builder' ? 'selected' : '' }}>
                                        Builder</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">RERA
                                    No.</label>
                                <input type="text" name="RERA_no"
                                    value="{{ old('RERA_no', $property['RERA_no'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Builder /
                                    developer</label>
                                <input type="text" name="builder_developer"
                                    value="{{ old('builder_developer', $property['builder_developer'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Area /
                                    Neighborhood</label>
                                <input type="text" name="area" value="{{ old('area', $property['area'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label
                                    class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Country</label>
                                <select id="country_id" name="country_id"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10">
                                    <option value="">Select country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ (int) old('country_id', $property->country_id ?? '') === $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">City</label>
                                <select id="city_id" name="city_id"
                                    class="city-select w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10"
                                    {{ $cities->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">Select city</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ (int) old('city_id', $property->city_id ?? '') === $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Zip</label>
                                <input type="text" name="zip" value="{{ old('zip', $property['zip'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                            <div>
                                <label
                                    class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Latitude</label>
                                <input type="text" name="map_latitude"
                                    value="{{ old('map_latitude', $property['map_latitude'] ?? '') }}"
                                    placeholder="40.7282"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label
                                    class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Longitude</label>
                                <input type="text" name="map_longitude"
                                    value="{{ old('map_longitude', $property['map_longitude'] ?? '') }}"
                                    placeholder="-74.0776"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Primary image
                                    URL</label>
                                <input type="text" name="primary_image"
                                    value="{{ old('primary_image', $property['primary_image'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <label
                                class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Description</label>
                            <textarea name="description" rows="5"
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10">{{ old('description', $property['description'] ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Gallery image
                                    1</label>
                                <input type="text" name="gallery_image_1"
                                    value="{{ old('gallery_image_1', $property['gallery_image_1'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-white">Gallery image
                                    2</label>
                                <input type="text" name="gallery_image_2"
                                    value="{{ old('gallery_image_2', $property['gallery_image_2'] ?? '') }}"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-brand-500/10" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <button
                                class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">{{ $mode === 'edit' ? 'Update Property' : 'Create Property' }}</button>
                            <a href="{{ route('admin.page', ['page' => 'properties']) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</a>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <style>
        .category-dropdown-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #d1d5db;
            background: #fff;
            padding: 0.75rem 1rem;
            text-align: left;
        }

        .category-dropdown-button:hover,
        .category-dropdown-button:focus-visible {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .category-dropdown-panel {
            min-width: 100%;
            transform-origin: top center;
        }

        .category-dropdown-panel.visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .category-dropdown-panel.invisible {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .category-selection-group {
            display: grid;
            gap: 0.75rem;
        }

        .category-parent-checkbox,
        .category-child-checkbox {
            accent-color: #2563eb;
            width: 1rem;
            height: 1rem;
        }

        .category-parent-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: #0f172a;
        }

        .category-child-list {
            margin-top: 0.75rem;
            padding-left: 1.5rem;
            display: grid;
            gap: 0.5rem;
        }

        .category-child-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #475569;
            font-size: 0.95rem;
        }

        /* Fallback layout: keep regular rows at two columns even if utility classes are overridden */
        .property-form>.grid.grid-cols-2.gap-4,
        .property-form>.grid.grid-cols-2.gap-4.mt-6 {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1rem !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.querySelector('.category-dropdown-button');
            const dropdownPanel = document.querySelector('.category-dropdown-panel');
            const categoryLabel = document.querySelector('.category-dropdown-label');
            const parents = document.querySelectorAll('.category-parent-checkbox');
            const children = document.querySelectorAll('.category-child-checkbox');
            const featureParents = document.querySelectorAll('.feature-parent-checkbox');
            const featureChildren = document.querySelectorAll('.feature-child-checkbox');
            const countrySelect = document.querySelector('#country_id');
            const stateSelect = document.querySelector('#state_id');
            const citySelect = document.querySelector('#city_id');
            const statesEndpoint = "{{ url('admin/states/by-country') }}";
            const citiesEndpoint = "{{ url('admin/cities/by-state') }}";

            const refreshSelectionLabel = function() {
                const selectedCount = document.querySelectorAll('input[name="category_id[]"]:checked').length;
                categoryLabel.textContent = selectedCount ? `${selectedCount} selected` : 'Select categories';
            };

            const populateSelect = function(select, items, placeholder) {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    select.appendChild(option);
                });
                select.disabled = items.length === 0;
            };

            const loadStates = function(countryId) {
                if (!countryId) {
                    populateSelect(stateSelect, [], 'Select state');
                    populateSelect(citySelect, [], 'Select city');
                    return;
                }

                fetch(`${statesEndpoint}/${countryId}`)
                    .then(response => response.json())
                    .then(data => {
                        populateSelect(stateSelect, data, 'Select state');
                        populateSelect(citySelect, [], 'Select city');
                    })
                    .catch(() => {
                        populateSelect(stateSelect, [], 'Select state');
                        populateSelect(citySelect, [], 'Select city');
                    });
            };

            const loadCities = function(stateId) {
                if (!stateId) {
                    populateSelect(citySelect, [], 'Select city');
                    return;
                }

                fetch(`${citiesEndpoint}/${stateId}`)
                    .then(response => response.json())
                    .then(data => {
                        populateSelect(citySelect, data, 'Select city');
                    })
                    .catch(() => {
                        populateSelect(citySelect, [], 'Select city');
                    });
            };

            if (countrySelect) {
                countrySelect.addEventListener('change', function() {
                    loadStates(this.value);
                });
            }

            if (stateSelect) {
                stateSelect.addEventListener('change', function() {
                    loadCities(this.value);
                });
            }

            const toggleDropdown = function() {
                const isOpen = dropdownPanel.classList.contains('visible');
                dropdownPanel.classList.toggle('visible', !isOpen);
                dropdownPanel.classList.toggle('invisible', isOpen);
                dropdownButton.setAttribute('aria-expanded', String(!isOpen));
            };

            const closeDropdown = function() {
                dropdownPanel.classList.remove('visible');
                dropdownPanel.classList.add('invisible');
                dropdownButton.setAttribute('aria-expanded', 'false');
            };

            if (dropdownButton) {
                dropdownButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    toggleDropdown();
                });
            }

            document.addEventListener('click', function(event) {
                if (!dropdownPanel.contains(event.target) && !dropdownButton.contains(event.target)) {
                    closeDropdown();
                }
            });

            parents.forEach(parent => {
                parent.addEventListener('change', function() {
                    const parentId = this.dataset.categoryId;
                    const childBoxes = document.querySelectorAll(
                        `.category-child-checkbox[data-parent-id="${parentId}"]`);
                    childBoxes.forEach(child => {
                        child.checked = this.checked;
                    });
                    refreshSelectionLabel();
                });
            });

            const updateParentState = function(child) {
                const parentId = child.dataset.parentId;
                if (!parentId) return;
                const parentBox = document.querySelector(
                    `.category-parent-checkbox[data-category-id="${parentId}"]`);
                const siblingChildren = document.querySelectorAll(
                    `.category-child-checkbox[data-parent-id="${parentId}"]`);
                if (!parentBox || siblingChildren.length === 0) return;
                const checkedCount = Array.from(siblingChildren).filter(el => el.checked).length;
                parentBox.checked = checkedCount === siblingChildren.length;
            };

            children.forEach(child => {
                child.addEventListener('change', function() {
                    updateParentState(this);
                    refreshSelectionLabel();
                });
            });

            const updateFeatureParentState = function(child) {
                const parentId = child.dataset.parentId;
                if (!parentId) return;
                const parentBox = document.querySelector(
                    `.feature-parent-checkbox[data-feature-id="${parentId}"]`);
                const siblingChildren = document.querySelectorAll(
                    `.feature-child-checkbox[data-parent-id="${parentId}"]`);
                if (!parentBox || siblingChildren.length === 0) return;
                const checkedCount = Array.from(siblingChildren).filter(el => el.checked).length;
                parentBox.checked = checkedCount === siblingChildren.length;
            };

            featureChildren.forEach(child => {
                child.addEventListener('change', function() {
                    updateFeatureParentState(this);
                });
            });

            featureParents.forEach(parent => {
                parent.addEventListener('change', function() {
                    const featureId = this.dataset.featureId;
                    const childBoxes = document.querySelectorAll(
                        `.feature-child-checkbox[data-parent-id="${featureId}"]`);
                    childBoxes.forEach(child => {
                        child.checked = this.checked;
                    });
                });
            });

            featureParents.forEach(parent => {
                const featureId = parent.dataset.featureId;
                const childBoxes = document.querySelectorAll(
                    `.feature-child-checkbox[data-parent-id="${featureId}"]`);
                if (childBoxes.length && Array.from(childBoxes).every(ch => ch.checked)) {
                    parent.checked = true;
                }
            });

            parents.forEach(parent => {
                const parentId = parent.dataset.categoryId;
                const childBoxes = document.querySelectorAll(
                    `.category-child-checkbox[data-parent-id="${parentId}"]`);
                if (childBoxes.length && Array.from(childBoxes).every(ch => ch.checked)) {
                    parent.checked = true;
                }
            });

            refreshSelectionLabel();
        });
    </script>
@endpush
