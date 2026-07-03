@php
    $latestProperties = [
        [
            'intent' => 'buy',
            'cityKey' => 'new-york',
            'typeKey' => 'villa',
            'beds' => 4,
            'priceValue' => 660000,
            'image' => 'https://images.unsplash.com/photo-1576941089067-2de3c901e126?auto=format&fit=crop&w=900&q=80',
            'title' => 'Luxury House in Greenville',
            'priceText' => '$660,000',
            'description' => 'This property is neatly assigned and is just high on a hilltop overlooking the Morning Rose Valley.',
            'location' => 'Greenville, New York',
            'badgeOne' => 'Sale',
            'badgeTwo' => 'Video',
        ],
        [
            'intent' => 'buy',
            'cityKey' => 'new-york',
            'typeKey' => 'apartment',
            'beds' => 2,
            'priceValue' => 150000,
            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80',
            'title' => 'Modern Condo for Sale',
            'priceText' => '$150,000',
            'description' => 'Beautiful, updated, grand loft condo apartment in the beautiful city of New York.',
            'location' => 'Manhattan, New York',
            'badgeOne' => 'New',
            'badgeTwo' => 'Home',
        ],
        [
            'intent' => 'rent',
            'cityKey' => 'new-york',
            'typeKey' => 'apartment',
            'beds' => 5,
            'priceValue' => 999,
            'image' => 'https://images.unsplash.com/photo-1502672023488-70e25813eb80?auto=format&fit=crop&w=900&q=80',
            'title' => 'Apartment with Subunits',
            'priceText' => '$999 / month',
            'description' => 'Streamlined international corporations are an additional option to increase sales in the area.',
            'location' => 'Bayonne, Jersey City',
            'badgeOne' => 'Rent',
            'badgeTwo' => 'Video',
        ],
        [
            'intent' => 'sell',
            'cityKey' => 'new-york',
            'typeKey' => 'apartment',
            'beds' => 3,
            'priceValue' => 550000,
            'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80',
            'title' => 'Three Room Apartment',
            'priceText' => '$550,000',
            'description' => 'Beautiful, huge 1 family home in heart of Westbury, nicely placed with modern details.',
            'location' => 'Westbury, New York',
            'badgeOne' => 'Invest',
            'badgeTwo' => 'Rent',
        ],
        [
            'intent' => 'sell',
            'cityKey' => 'new-york',
            'typeKey' => 'townhouse',
            'beds' => 4,
            'priceValue' => 1000000,
            'image' => 'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=900&q=80',
            'title' => 'Suburb Home for Sale',
            'priceText' => '$1,000,000',
            'description' => 'Beautiful, 1900ft, newly built 3000 sq ft building that could be used as a great investment.',
            'location' => 'Long Island, New York',
            'badgeOne' => 'Invest',
            'badgeTwo' => 'Video',
        ],
        [
            'intent' => 'buy',
            'cityKey' => 'new-york',
            'typeKey' => 'villa',
            'beds' => 5,
            'priceValue' => 770000,
            'image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=900&q=80',
            'title' => 'Gorgeous Home for Sale in Jersey',
            'priceText' => '$770,000',
            'description' => 'This property is neatly assigned and sits high on a hilltop overlooking the Morning Rose Valley.',
            'location' => 'Jersey City, New Jersey',
            'badgeOne' => 'Sale',
            'badgeTwo' => 'Video',
        ],
    ];
@endphp

<section class="bg-white py-12 sm:py-16" id="latest">
    <div class="section-shell">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="section-title">Latest Properties</h2>
            <p class="section-copy mx-auto mt-4 max-w-3xl">
                These are the latest properties in the sales category. You can create the list using
                the latest listing shortcode and show items by specific categories.
            </p>
        </div>

        <div class="mx-auto mt-6 flex w-fit flex-wrap justify-center gap-2 rounded-lg bg-slate-50 p-1 shadow-sm ring-1 ring-slate-200">
            <span class="rounded-md bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">For Sale</span>
            <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Villas</span>
            <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Apartments</span>
            <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Houses</span>
            <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Condos</span>
            <span class="rounded-md px-4 py-2 text-sm font-medium text-slate-500">Retail</span>
        </div>

        <p id="results-count" class="mx-auto mt-5 max-w-6xl text-sm font-semibold text-slate-500">Showing 0 matching listings</p>

        <div class="mx-auto mt-6 grid max-w-6xl gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($latestProperties as $property)
                @include('partials.home.cards.property-card', $property)
            @endforeach
        </div>

        <p id="no-results" class="mx-auto mt-6 hidden max-w-6xl rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">
            No matching listings found. Try adjusting your filters.
        </p>

        <div class="mt-6 text-center">
            <button class="rounded-md bg-[#0f6fcb] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#0c5eac]">Load More Listings</button>
        </div>
    </div>
</section>
