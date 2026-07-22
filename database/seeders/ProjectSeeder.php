<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Property;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or retrieve base geographical info
        $country = Country::firstOrCreate(['name' => 'India']);
        $state = State::firstOrCreate(['name' => 'Delhi', 'country_id' => $country->id]);
        $city = City::firstOrCreate([
            'name' => 'New Delhi', 
            'state_id' => $state->id,
            'country_id' => $country->id
        ]);

        // 2. Create or retrieve Category
        $category = Category::firstOrCreate(['name' => 'Apartment']);

        // 3. Create Projects
        $proj1 = Project::create([
            'name' => 'Green Heights',
            'slug' => 'green-heights',
            'description' => 'A premier residential luxury project featuring premium 2BHK and 3BHK high-rise apartments with state of the art amenities, including club house, swimming pool, and round the clock security.',
            'location' => 'Sector 62, Noida, Delhi NCR',
            'total_units' => 50,
            'status' => 'under_construction',
        ]);

        $proj2 = Project::create([
            'name' => 'Royal Residency',
            'slug' => 'royal-residency',
            'description' => 'Ultra luxury villas and duplex penthouses situated in whitefield. Designed for modern living with private gardens and home automation systems.',
            'location' => 'Whitefield, Bangalore',
            'total_units' => 30,
            'status' => 'planning',
        ]);

        // 4. Create Property Units (Inventory Grid) for Green Heights
        $towers = ['Tower A', 'Tower B'];
        $floors = ['1', '2', '3'];
        $units = ['01', '02', '03'];
        $statuses = ['available', 'sold', 'reserved', 'blocked'];

        foreach ($towers as $tIdx => $tower) {
            foreach ($floors as $floor) {
                foreach ($units as $uIdx => $unitSuffix) {
                    $unitNumber = $floor . $unitSuffix;
                    $status = $statuses[($tIdx + (int)$floor + $uIdx) % 4];

                    $prop = Property::create([
                        'title' => "Green Heights {$tower} Unit {$unitNumber}",
                        'slug' => "green-heights-".Str::slug($tower)."-unit-{$unitNumber}-".uniqid(),
                        'description' => "Premium apartment unit {$unitNumber} located in {$tower} on floor {$floor}.",
                        'price' => 7500000 + ((int)$floor * 250000),
                        'status' => 'active',
                        'project_id' => $proj1->id,
                        'tower' => $tower,
                        'floor' => $floor,
                        'unit_number' => $unitNumber,
                        'inventory_status' => $status,
                        'facing' => (($uIdx % 2) === 0) ? 'East' : 'West',
                        'parking_slots' => 1,
                        'city_id' => $city->id,
                        'state_id' => $state->id,
                        'country_id' => $country->id,
                        'address_line' => 'Sector 62, Noida',
                    ]);

                    $prop->categories()->sync([$category->id]);
                }
            }
        }

        // 5. Create some units for Royal Residency
        $towers2 = ['Villas'];
        $floors2 = ['1', '2'];
        $units2 = ['101', '102', '201', '202'];

        foreach ($towers2 as $tower) {
            foreach ($floors2 as $floor) {
                foreach ($units2 as $unitNumber) {
                    $prop = Property::create([
                        'title' => "Royal Residency Duplex Villa {$unitNumber}",
                        'slug' => "royal-residency-villa-{$unitNumber}-".uniqid(),
                        'description' => "Duplex luxury villa unit {$unitNumber}.",
                        'price' => 18000000,
                        'status' => 'active',
                        'project_id' => $proj2->id,
                        'tower' => $tower,
                        'floor' => $floor,
                        'unit_number' => $unitNumber,
                        'inventory_status' => 'available',
                        'facing' => 'East',
                        'parking_slots' => 2,
                        'city_id' => $city->id,
                        'state_id' => $state->id,
                        'country_id' => $country->id,
                        'address_line' => 'Whitefield, Bangalore',
                    ]);

                    $prop->categories()->sync([$category->id]);
                }
            }
        }
    }
}
