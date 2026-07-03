@php
    $rentalProperties = [
        [
            'image' => 'https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=900&q=80',
            'title' => 'Apartment with Subunits',
            'status' => 'Active',
            'featured' => true,
            'location' => 'Greenville, Jersey City',
            'tag' => '7',
            'price' => '$999 / month',
            'description' => "Sesame Street international co-productions are educational children's television series.",
            'beds' => '5',
            'baths' => '2',
            'size' => '1,900 ft²',
            'agent' => 'Lora Smith',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80',
            'title' => 'New York Studio Apartment',
            'status' => 'Active',
            'featured' => false,
            'location' => 'Manhattan, New York',
            'tag' => '6',
            'price' => '$2,000 / year',
            'description' => 'This property is mostly wooded and sits high on a hilltop overlooking the Mohawk River Valley.',
            'beds' => '1',
            'baths' => '1',
            'size' => '120 ft²',
            'agent' => 'Green Reality',
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80',
            'title' => 'Luxury Home in Manhattan',
            'status' => 'Open House',
            'featured' => false,
            'location' => 'Manhattan, New York',
            'tag' => '7',
            'price' => '$450 / month',
            'description' => 'Just steps away from QM2 express bus to Manhattan and local buses; only minutes from the L train.',
            'beds' => '2',
            'baths' => '3',
            'size' => '120 ft²',
            'agent' => 'Green Reality',
        ],
    ];
@endphp

<section class="bg-[#f5f7fb] py-12 sm:py-16">
    <div class="section-shell relative">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="section-title">Properties for Rent</h2>
            <p class="section-copy mx-auto mt-4 max-w-3xl">
                These are the latest properties in the sales category. You can create the list using
                the "latest listing shortcode" and show items by specific categories.
            </p>
        </div>

        <button class="absolute left-0 top-1/2 hidden -translate-x-5 rounded bg-[#e8edf7] p-2 text-[#2f6dd7] shadow-sm md:block" aria-label="Previous slide">&larr;</button>
        <button class="absolute right-0 top-1/2 hidden translate-x-5 rounded bg-[#e8edf7] p-2 text-[#2f6dd7] shadow-sm md:block" aria-label="Next slide">&rarr;</button>

        <div class="mx-auto mt-8 grid max-w-6xl gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($rentalProperties as $property)
                @include('partials.home.cards.rental-card', $property)
            @endforeach
        </div>

        <div class="mt-6 flex justify-center gap-3">
            <span class="h-2.5 w-2.5 rounded-full bg-[#2f6dd7]"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
        </div>
    </div>
</section>
