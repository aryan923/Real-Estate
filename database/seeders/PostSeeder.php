<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogPosts = [
            [
                'title' => 'Manhattan Apartments',
                'category' => 'City Living',
                'date' => 'May 27, 2014',
                'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1400&q=80',
                'excerpt' => 'In Manhattan, uptown means north (more precisely north-northeast, which is the direction the island and its street grid are oriented) and downtown means south.',
                'content' => "Manhattan continues to be one of the most dynamic real estate markets in the world, with apartments that cater to a wide range of lifestyles and budgets. The borough’s grid, transit access, and cultural density keep demand strong even when broader market conditions shift.\n\nFor buyers and renters, the biggest advantage is clarity. Neighborhoods can be compared by commute times, building amenities, and access to parks, dining, and schools. That makes it easier to match a property with long-term goals instead of just headline price.\n\nIf you are evaluating Manhattan apartments, focus on the building’s management, monthly carrying costs, and the quality of the immediate block. Those details often matter more than square footage alone when it comes to everyday livability and resale value.",
            ],
            [
                'title' => 'Why Live in New York',
                'category' => 'Neighborhood',
                'date' => 'May 27, 2014',
                'image' => 'https://images.unsplash.com/photo-1617104551722-3b2d51366400?auto=format&fit=crop&w=1400&q=80',
                'excerpt' => 'In contrast with New York City urban atmosphere, the vast majority of the state is dominated by farms, forests, rivers, mountains and lakes.',
                'content' => "New York City remains a magnet for people who want access to opportunity, culture, and a highly connected urban lifestyle. Beyond the skyline, each neighborhood has its own pace, identity, and real estate profile.\n\nWhat makes the city distinctive is the mix of architecture, transportation, and local character. Buyers often move here for career reasons, but they stay because the neighborhood experience feels personal and highly walkable.\n\nChoosing the right area means comparing more than price per square foot. Evaluate commute routes, building age, rental rules, and the daily rhythm of the block before you commit to a purchase or lease.",
            ],
            [
                'title' => 'What Luxury Renters Are Prioritizing This Year',
                'category' => 'Market Trends',
                'date' => 'June 18, 2026',
                'image' => 'https://images.unsplash.com/photo-1501183638710-841dd1904471?auto=format&fit=crop&w=1400&q=80',
                'excerpt' => 'From wellness-driven layouts to energy-smart systems, learn which features are driving premium lease decisions.',
                'content' => "Luxury renters are paying closer attention to what helps them live well every day. Wellness spaces, better natural light, and quiet interiors now carry as much weight as finishes and square footage.\n\nTechnology also plays a larger role. Smart access, package handling, and efficient climate control can push a building ahead similar listings that feel more traditional or less convenient.\n\nFor landlords and brokers, the takeaway is simple: premium renters want a smoother experience from the first viewing through move-in. Properties that reduce friction tend to lease faster and retain tenants longer.",
            ],
            [
                'title' => 'Renovation ROI: Which Upgrades Actually Increase Value',
                'category' => 'Home Value',
                'date' => 'June 10, 2026',
                'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1400&q=80',
                'excerpt' => 'Not every improvement adds equal return. Here is where homeowners are seeing the strongest resale gains right now.',
                'content' => "Renovations can increase value, but the return depends on the neighborhood, the current condition of the home, and how buyers in that market prioritize upgrades. Kitchens, baths, and exterior curb appeal remain the most dependable categories.\n\nThe best projects usually solve a visible problem rather than chase style alone. Better layouts, improved storage, and energy-efficient systems often matter more than highly personalized design choices.\n\nBefore you spend heavily, compare the likely after-repair value to nearby sales and plan upgrades that bring the home up to market expectations without overshooting the area’s ceiling.",
            ],
            [
                'title' => 'How to Evaluate a Neighborhood Like a Local Expert',
                'category' => 'Buying Guide',
                'date' => 'June 1, 2026',
                'image' => 'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=1400&q=80',
                'excerpt' => 'A field-tested framework for comparing commute access, long-term growth indicators, and quality-of-life signals.',
                'content' => "A strong neighborhood evaluation starts with the basics: access, safety, and convenience. Once those are covered, look at the long-term indicators that shape future demand and resale performance.\n\nWalk the area at different times of day. Traffic patterns, noise levels, retail activity, and even sidewalk quality can reveal more than a quick online search or a single afternoon showing.\n\nThe best local experts compare lifestyle fit as carefully as investment potential. That balance usually leads to better decisions because the property has to work both on paper and in daily life.",
            ],
            [
                'title' => 'Rental Demand Snapshot: Where Investors Are Looking',
                'category' => 'Investing',
                'date' => 'May 24, 2026',
                'image' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1400&q=80',
                'excerpt' => 'A quick look at rental demand shifts and the property types drawing the most interest from experienced investors.',
                'content' => "Investors are still focused on markets with durable rental demand and clear job growth. The properties drawing the most attention are those that combine manageable maintenance with broad tenant appeal.\n\nSmall multifamily, well-located apartments, and homes near transit or employment corridors continue to look attractive because they reduce vacancy risk and support more predictable cash flow.\n\nA disciplined investor reads local supply, rent growth, and absorption together. That wider view helps distinguish short-term noise from real opportunity.",
            ],
        ];

        foreach ($blogPosts as $post) {
            Post::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'category' => $post['category'],
                    'date' => $post['date'],
                    'image' => $post['image'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'author' => 'admin',
                    'status' => true,
                ]
            );
        }
    }
}
