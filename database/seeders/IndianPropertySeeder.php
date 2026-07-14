<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Feature;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class IndianPropertySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or get Country India
        $country = Country::updateOrCreate(
            ['iso2' => 'IN'],
            [
                'name' => 'India',
                'iso3' => 'IND',
                'phonecode' => '91',
                'capital' => 'New Delhi',
                'currency' => 'INR',
                'currency_symbol' => '₹',
                'region' => 'Asia',
                'subregion' => 'Southern Asia',
                'latitude' => 20.593684,
                'longitude' => 78.962880,
                'emoji' => '🇮🇳',
            ]
        );

        // 2. Create or get States
        $maharashtra = State::updateOrCreate(
            ['country_id' => $country->id, 'iso2' => 'MH'],
            ['name' => 'Maharashtra', 'country_code' => 'IN']
        );

        $karnataka = State::updateOrCreate(
            ['country_id' => $country->id, 'iso2' => 'KA'],
            ['name' => 'Karnataka', 'country_code' => 'IN']
        );

        $delhiState = State::updateOrCreate(
            ['country_id' => $country->id, 'iso2' => 'DL'],
            ['name' => 'Delhi', 'country_code' => 'IN']
        );

        $haryana = State::updateOrCreate(
            ['country_id' => $country->id, 'iso2' => 'HR'],
            ['name' => 'Haryana', 'country_code' => 'IN']
        );

        // 3. Create or get Cities
        $mumbai = City::updateOrCreate(
            ['state_id' => $maharashtra->id, 'name' => 'Mumbai'],
            ['country_id' => $country->id, 'state_code' => 'MH', 'country_code' => 'IN', 'is_featured' => true]
        );

        $pune = City::updateOrCreate(
            ['state_id' => $maharashtra->id, 'name' => 'Pune'],
            ['country_id' => $country->id, 'state_code' => 'MH', 'country_code' => 'IN', 'is_featured' => true]
        );

        $bangalore = City::updateOrCreate(
            ['state_id' => $karnataka->id, 'name' => 'Bangalore'],
            ['country_id' => $country->id, 'state_code' => 'KA', 'country_code' => 'IN', 'is_featured' => true]
        );

        $newDelhi = City::updateOrCreate(
            ['state_id' => $delhiState->id, 'name' => 'New Delhi'],
            ['country_id' => $country->id, 'state_code' => 'DL', 'country_code' => 'IN', 'is_featured' => true]
        );

        $gurgaon = City::updateOrCreate(
            ['state_id' => $haryana->id, 'name' => 'Gurgaon'],
            ['country_id' => $country->id, 'state_code' => 'HR', 'country_code' => 'IN', 'is_featured' => true]
        );

        // Clear existing properties and related tables
        Schema::disableForeignKeyConstraints();
        DB::table('property_categories')->truncate();
        DB::table('property_features')->truncate();
        DB::table('property_images')->truncate();
        DB::table('enquiries')->truncate();
        DB::table('properties')->truncate();
        Schema::enableForeignKeyConstraints();

        // 4. Retrieve Categories
        $apartmentCat = Category::where('name', 'Apartment / Flat')->first() ?: Category::first();
        $villaCat = Category::where('name', 'Villa')->first() ?: Category::first();
        $penthouseCat = Category::where('name', 'Penthouse')->first() ?: Category::first();
        $bungalowCat = Category::where('name', 'Bungalow')->first() ?: Category::first();
        $officeCat = Category::where('name', 'Office Space')->first() ?: Category::first();
        $coworkingCat = Category::where('name', 'Co-working Space')->first() ?: Category::first();

        // Retrieve Features
        $features = Feature::limit(5)->get();

        // 5. Setup Indian Property Dataset with 99acres extracted images
        $propertiesData = [
            [
                'title' => '3 BHK Luxury Apartment in Bandra West',
                'description' => 'Experience premium luxury living in the heart of Bandra West. This sea-facing apartment offers state of the art amenities, a spacious modular kitchen, luxury bathrooms, and Italian marble flooring throughout. Close to top schools, celebrity neighborhoods, and elite shopping hubs.',
                'price' => 45000000,
                'price_type' => 'sale',
                'price_info' => '₹1,50,000 / sq ft',
                'property_purpose' => 'sale',
                'listing_type' => 'agent',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'rooms' => 6,
                'size' => 1850,
                'locality' => 'Bandra West',
                'city_id' => $mumbai->id,
                'state_id' => $maharashtra->id,
                'country_id' => $country->id,
                'zip' => '400050',
                'featured' => true,
                'category' => $apartmentCat,
                'images' => [
                    'https://imagecdn.99acres.com/media1/39281/6/785626738M-1782799088282.jpg',
                    'https://newprojects.99acres.com/media/xop/2026/06/17/10/i53penv_1781673591_778551828_med.jpg',
                ]
            ],
            [
                'title' => 'Elegant 2 BHK Flat in Andheri East',
                'description' => 'Modern 2 BHK apartment for rent in Andheri East. Fully furnished with modular wardrobes, modern kitchen chimney, smart home automation, and 24/7 power backup. Ideal for working professionals and families looking for easy metro and highway connectivity.',
                'price' => 65000,
                'price_type' => 'month',
                'price_info' => '₹65,000 / month',
                'property_purpose' => 'rent',
                'listing_type' => 'agent',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'rooms' => 4,
                'size' => 1100,
                'locality' => 'Andheri East',
                'city_id' => $mumbai->id,
                'state_id' => $maharashtra->id,
                'country_id' => $country->id,
                'zip' => '400069',
                'featured' => false,
                'category' => $apartmentCat,
                'images' => [
                    'https://newprojects.99acres.com/media/xop/2025/12/23/13/rsgvrbz_1766477199_689649337_med.jpg',
                    'https://newprojects.99acres.com/media/xop/2026/05/13/15/3kbbuht_1778666292_758671180_med.jpg',
                ]
            ],
            [
                'title' => 'Ultra Premium Penthouse in Koregaon Park',
                'description' => 'A stunning 5 BHK Duplex Penthouse overlooking the scenic greenery of Koregaon Park, Pune. Features an expansive private terrace garden, dedicated home theatre room, private swimming pool, and custom interiors. Situated in Pune\'s most prestigious location.',
                'price' => 75000000,
                'price_type' => 'sale',
                'price_info' => '₹40,000 / sq ft',
                'property_purpose' => 'sale',
                'listing_type' => 'agent',
                'bedrooms' => 5,
                'bathrooms' => 6,
                'rooms' => 10,
                'size' => 4200,
                'locality' => 'Koregaon Park',
                'city_id' => $pune->id,
                'state_id' => $maharashtra->id,
                'country_id' => $country->id,
                'zip' => '411001',
                'featured' => true,
                'category' => $penthouseCat,
                'images' => [
                    'https://newprojects.99acres.com/media/xop/2026/06/17/10/2qom6yd_1781673580_778551756_med.jpg',
                    'https://imagecdn.99acres.com/media1/15694/16/313896566M-1629572544993.jpg',
                ]
            ],
            [
                'title' => 'Spacious 4 BHK Villa in Whitefield',
                'description' => 'Luxurious independent 4 BHK villa located inside a gated community in Whitefield, Bangalore. Features a private lawn, Italian design kitchen, solar water heating, and a spacious clubhouse inside the community. Just minutes away from IT parks and international schools.',
                'price' => 62000000,
                'price_type' => 'sale',
                'price_info' => '₹18,000 / sq ft',
                'property_purpose' => 'sale',
                'listing_type' => 'agent',
                'bedrooms' => 4,
                'bathrooms' => 4,
                'rooms' => 8,
                'size' => 3400,
                'locality' => 'Whitefield',
                'city_id' => $bangalore->id,
                'state_id' => $karnataka->id,
                'country_id' => $country->id,
                'zip' => '560066',
                'featured' => true,
                'category' => $villaCat,
                'images' => [
                    'https://imagecdn.99acres.com/media1/15694/16/313896168M-1629571715766.jpg',
                    'https://newprojects.99acres.com/media/xop/2026/06/16/9/smies3e_1781582380_777920524_med.jpg',
                ]
            ],
            [
                'title' => 'Fully Furnished Office Space in Connaught Place',
                'description' => 'Premium, corporate office space for rent in Connaught Place. Plug-and-play layout with 40 workstations, 3 meeting rooms, a pantry, and reception lobby. Perfect for corporate offices, law firms, and consulting groups.',
                'price' => 250000,
                'price_type' => 'month',
                'price_info' => '₹2.5 Lakh / month',
                'property_purpose' => 'rent',
                'listing_type' => 'agent',
                'bedrooms' => 0,
                'bathrooms' => 2,
                'rooms' => 5,
                'size' => 2400,
                'locality' => 'Connaught Place',
                'city_id' => $newDelhi->id,
                'state_id' => $delhiState->id,
                'country_id' => $country->id,
                'zip' => '110001',
                'featured' => false,
                'category' => $officeCat,
                'images' => [
                    'https://newprojects.99acres.com/media/xop/2026/05/13/15/hpioe6w_1778666294_758671200_med.jpg',
                ]
            ],
            [
                'title' => 'Modern Studio Apartment in DLF Phase 5',
                'description' => 'A cozy, high-end studio apartment for rent in DLF Phase 5, Gurgaon. Features chic interiors, central AC, modular pantry, large windows with city skyline views, and excellent security. Near top corporate tech parks and restaurants.',
                'price' => 45000,
                'price_type' => 'month',
                'price_info' => '₹45,000 / month',
                'property_purpose' => 'rent',
                'listing_type' => 'agent',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'rooms' => 2,
                'size' => 650,
                'locality' => 'DLF Phase 5',
                'city_id' => $gurgaon->id,
                'state_id' => $haryana->id,
                'country_id' => $country->id,
                'zip' => '122002',
                'featured' => false,
                'category' => $apartmentCat,
                'images' => [
                    'https://newprojects.99acres.com/media/xop/2026/06/17/10/d5ukm5g_1781673577_778551736_med.jpg',
                ]
            ],
            [
                'title' => 'Contemporary Bungalow in Juhu Scheme',
                'description' => 'Exclusive independent bungalow in Mumbai\'s upscale Juhu Scheme neighborhood. Features a private swimming pool, landscaped gardens, multiple parking spaces, and direct beach access. High security gated perimeter, perfect for high-profile residents.',
                'price' => 150000000,
                'price_type' => 'sale',
                'price_info' => '₹1.5 Lakh / sq ft',
                'property_purpose' => 'sale',
                'listing_type' => 'agent',
                'bedrooms' => 5,
                'bathrooms' => 6,
                'rooms' => 12,
                'size' => 6500,
                'locality' => 'Juhu Scheme',
                'city_id' => $mumbai->id,
                'state_id' => $maharashtra->id,
                'country_id' => $country->id,
                'zip' => '400049',
                'featured' => true,
                'category' => $bungalowCat,
                'images' => [
                    'https://newprojects.99acres.com/media/xop/2025/12/23/13/qsjimla_1766477128_689648657_med.jpg',
                    'https://newprojects.99acres.com/media/xop/2026/04/23/12/rahe9qf_1776926246_748027666_med.jpg',
                ]
            ],
            [
                'title' => 'Premium Co-working Space in HSR Layout',
                'description' => 'Highly functional co-working space located in Bangalore\'s prime start-up corridor HSR Layout. Offering flexible hot desks, dedicated desks, and private cabins. Features conference rooms, high-speed fiber internet, cafe area, and event zone.',
                'price' => 350000,
                'price_type' => 'month',
                'price_info' => '₹3.5 Lakh / month',
                'property_purpose' => 'lease',
                'listing_type' => 'agent',
                'bedrooms' => 0,
                'bathrooms' => 4,
                'rooms' => 12,
                'size' => 4800,
                'locality' => 'HSR Layout',
                'city_id' => $bangalore->id,
                'state_id' => $karnataka->id,
                'country_id' => $country->id,
                'zip' => '560102',
                'featured' => false,
                'category' => $coworkingCat,
                'images' => [
                    'https://newprojects.99acres.com/media/xop/2026/06/16/9/9boc2vb_1781582387_777920544_med.jpg',
                ]
            ]
        ];

        // Seed properties
        foreach ($propertiesData as $prop) {
            $createdProperty = Property::create([
                'title' => $prop['title'],
                'slug' => Str::slug($prop['title']),
                'description' => $prop['description'],
                'price' => $prop['price'],
                'price_type' => $prop['price_type'],
                'price_info' => $prop['price_info'],
                'status' => 'active',
                'featured' => $prop['featured'],
                'property_code' => 'PROP-' . strtoupper(Str::random(6)),
                'property_purpose' => $prop['property_purpose'],
                'listing_type' => $prop['listing_type'],
                'bedrooms' => $prop['bedrooms'],
                'bathrooms' => $prop['bathrooms'],
                'rooms' => $prop['rooms'],
                'size' => $prop['size'],
                'locality' => $prop['locality'],
                'city_id' => $prop['city_id'],
                'state_id' => $prop['state_id'],
                'country_id' => $prop['country_id'],
                'zip' => $prop['zip'],
            ]);

            // Attach Category
            if ($prop['category']) {
                $createdProperty->categories()->attach($prop['category']->id);
            }

            // Attach Features
            if ($features->isNotEmpty()) {
                $createdProperty->features()->attach($features->pluck('id')->toArray());
            }

            // Seed Images
            foreach ($prop['images'] as $index => $imgUrl) {
                PropertyImage::create([
                    'property_id' => $createdProperty->id,
                    'path' => $imgUrl,
                    'is_primary' => ($index === 0),
                    'sort_order' => $index,
                ]);
            }
        }

        $this->command->info("Indian property data seeded successfully!");
    }
}
