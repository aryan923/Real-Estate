<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$blogPosts = [
    [
        'title' => 'Manhattan Apartments',
        'slug' => 'manhattan-apartments',
        'excerpt' => 'In Manhattan, uptown means north (more precisely north-northeast, which is the direction the island and its street grid are oriented) and downtown means south.',
        'category' => 'City Living',
        'date' => 'May 27, 2014',
        'read_time' => '5 min read',
        'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1400&q=80',
        'content' => [
            'Manhattan continues to be one of the most dynamic real estate markets in the world, with apartments that cater to a wide range of lifestyles and budgets. The borough’s grid, transit access, and cultural density keep demand strong even when broader market conditions shift.',
            'For buyers and renters, the biggest advantage is clarity. Neighborhoods can be compared by commute times, building amenities, and access to parks, dining, and schools. That makes it easier to match a property with long-term goals instead of just headline price.',
            'If you are evaluating Manhattan apartments, focus on the building’s management, monthly carrying costs, and the quality of the immediate block. Those details often matter more than square footage alone when it comes to everyday livability and resale value.',
        ],
    ],
    [
        'title' => 'Why Live in New York',
        'slug' => 'why-live-in-new-york',
        'excerpt' => 'In contrast with New York City urban atmosphere, the vast majority of the state is dominated by farms, forests, rivers, mountains and lakes.',
        'category' => 'Neighborhood',
        'date' => 'May 27, 2014',
        'read_time' => '4 min read',
        'image' => 'https://images.unsplash.com/photo-1617104551722-3b2d51366400?auto=format&fit=crop&w=1400&q=80',
        'content' => [
            'New York City remains a magnet for people who want access to opportunity, culture, and a highly connected urban lifestyle. Beyond the skyline, each neighborhood has its own pace, identity, and real estate profile.',
            'What makes the city distinctive is the mix of architecture, transportation, and local character. Buyers often move here for career reasons, but they stay because the neighborhood experience feels personal and highly walkable.',
            'Choosing the right area means comparing more than price per square foot. Evaluate commute routes, building age, rental rules, and the daily rhythm of the block before you commit to a purchase or lease.',
        ],
    ],
    [
        'title' => 'What Luxury Renters Are Prioritizing This Year',
        'slug' => 'what-luxury-renters-are-prioritizing-this-year',
        'excerpt' => 'From wellness-driven layouts to energy-smart systems, learn which features are driving premium lease decisions.',
        'category' => 'Market Trends',
        'date' => 'June 18, 2026',
        'read_time' => '5 min read',
        'image' => 'https://images.unsplash.com/photo-1501183638710-841dd1904471?auto=format&fit=crop&w=1400&q=80',
        'content' => [
            'Luxury renters are paying closer attention to what helps them live well every day. Wellness spaces, better natural light, and quiet interiors now carry as much weight as finishes and square footage.',
            'Technology also plays a larger role. Smart access, package handling, and efficient climate control can push a building ahead of similar listings that feel more traditional or less convenient.',
            'For landlords and brokers, the takeaway is simple: premium renters want a smoother experience from the first viewing through move-in. Properties that reduce friction tend to lease faster and retain tenants longer.',
        ],
    ],
    [
        'title' => 'Renovation ROI: Which Upgrades Actually Increase Value',
        'slug' => 'renovation-roi-which-upgrades-actually-increase-value',
        'excerpt' => 'Not every improvement adds equal return. Here is where homeowners are seeing the strongest resale gains right now.',
        'category' => 'Home Value',
        'date' => 'June 10, 2026',
        'read_time' => '7 min read',
        'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1400&q=80',
        'content' => [
            'Renovations can increase value, but the return depends on the neighborhood, the current condition of the home, and how buyers in that market prioritize upgrades. Kitchens, baths, and exterior curb appeal remain the most dependable categories.',
            'The best projects usually solve a visible problem rather than chase style alone. Better layouts, improved storage, and energy-efficient systems often matter more than highly personalized design choices.',
            'Before you spend heavily, compare the likely after-repair value to nearby sales and plan upgrades that bring the home up to market expectations without overshooting the area’s ceiling.',
        ],
    ],
    [
        'title' => 'How to Evaluate a Neighborhood Like a Local Expert',
        'slug' => 'how-to-evaluate-a-neighborhood-like-a-local-expert',
        'excerpt' => 'A field-tested framework for comparing commute access, long-term growth indicators, and quality-of-life signals.',
        'category' => 'Buying Guide',
        'date' => 'June 1, 2026',
        'read_time' => '6 min read',
        'image' => 'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=1400&q=80',
        'content' => [
            'A strong neighborhood evaluation starts with the basics: access, safety, and convenience. Once those are covered, look at the long-term indicators that shape future demand and resale performance.',
            'Walk the area at different times of day. Traffic patterns, noise levels, retail activity, and even sidewalk quality can reveal more than a quick online search or a single afternoon showing.',
            'The best local experts compare lifestyle fit as carefully as investment potential. That balance usually leads to better decisions because the property has to work both on paper and in daily life.',
        ],
    ],
    [
        'title' => 'Rental Demand Snapshot: Where Investors Are Looking',
        'slug' => 'rental-demand-snapshot-where-investors-are-looking',
        'excerpt' => 'A quick look at rental demand shifts and the property types drawing the most interest from experienced investors.',
        'category' => 'Investing',
        'date' => 'May 24, 2026',
        'read_time' => '5 min read',
        'image' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1400&q=80',
        'content' => [
            'Investors are still focused on markets with durable rental demand and clear job growth. The properties drawing the most attention are those that combine manageable maintenance with broad tenant appeal.',
            'Small multifamily, well-located apartments, and homes near transit or employment corridors continue to look attractive because they reduce vacancy risk and support more predictable cash flow.',
            'A disciplined investor reads local supply, rent growth, and absorption together. That wider view helps distinguish short-term noise from real opportunity.',
        ],
    ],
];

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/properties', function () {
    return view('properties');
})->name('properties');

Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact-us');

Route::get('/agents', function () {
    return view('agents');
})->name('agents');

Route::get('/blog', function () use ($blogPosts) {
    return view('blog', ['allPosts' => $blogPosts]);
})->name('blog');

Route::get('/blog/{slug}', function (string $slug) use ($blogPosts) {
    $post = collect($blogPosts)->firstWhere('slug', Str::slug($slug));
    abort_unless($post, 404);

    return view('blog-detail', [
        'post' => $post,
        'recentPosts' => array_slice($blogPosts, 0, 4),
    ]);
})->name('blog-detail');

Route::get('/agents/{slug}', function (string $slug) {
    $agents = [
        [
            'name' => 'Amanda Smithson',
            'slug' => 'amanda-smithson',
            'role' => 'Senior Manager',
            'company' => 'Lux Estate',
            'reviews' => 3,
            'member_of' => 'National Association of Luxury Real Estate Professionals',
            'address' => '120 Greenwich St, Suite 220, Manhattan, New York, NY 10006',
            'phone' => '1-800-555-1021',
            'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Samuel Diesel',
            'slug' => 'samuel-diesel',
            'role' => 'Commercial Broker',
            'company' => 'Lux Estate',
            'reviews' => 1,
            'member_of' => 'National Association of Luxury Real Estate Professionals',
            'address' => '1234 Beacon Street, Suite 101, Floor 3 The Harrison Tower Back Bay, Boston, MA 02118',
            'phone' => '1-800-555-1234',
            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Lily Bicharm',
            'slug' => 'lily-bicharm',
            'role' => 'Residential Agent',
            'company' => 'Lux Estate',
            'reviews' => 2,
            'member_of' => 'National Association of Luxury Real Estate Professionals',
            'address' => '370 Bay Street, Level 9, Toronto, ON M5H 2R2',
            'phone' => '1-800-555-1933',
            'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Dennis Albo',
            'slug' => 'dennis-albo',
            'role' => 'Investment Advisor',
            'company' => 'Lux Estate',
            'reviews' => 2,
            'member_of' => 'National Association of Luxury Real Estate Professionals',
            'address' => '99 King Street, Suite 12, Seattle, WA 98104',
            'phone' => '1-800-555-4020',
            'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Maria Fields',
            'slug' => 'maria-fields',
            'role' => 'Leasing Specialist',
            'company' => 'Lux Estate',
            'reviews' => 4,
            'member_of' => 'National Association of Luxury Real Estate Professionals',
            'address' => '55 Bayshore Ave, Unit 8, San Diego, CA 92101',
            'phone' => '1-800-555-5531',
            'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'name' => 'Michael Sutther',
            'slug' => 'michael-sutther',
            'role' => 'Realtor',
            'company' => 'Lux Estate',
            'reviews' => 1,
            'member_of' => 'National Association of Luxury Real Estate Professionals',
            'address' => '481 Harbor Drive, Suite 30, Miami, FL 33132',
            'phone' => '1-800-555-8842',
            'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=900&q=80',
        ],
    ];

    $agent = collect($agents)->firstWhere('slug', Str::slug($slug));
    abort_unless($agent, 404);

    return view('agent-detail', ['agent' => $agent]);
})->name('agent-detail');

Route::get('/property-detail', function () {
    return view('property-detail');
})->name('property-detail');
