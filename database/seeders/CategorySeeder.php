<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = [
            'Residential' => [
                'Apartment / Flat',
                'Builder Floor',
                'Independent House',
                'Villa',
                'Bungalow',
                'Duplex',
                'Triplex',
                'Penthouse',
                'Studio Apartment',
                'Row House',
                'Farm House',
                'Residential Plot',
                'Residential Land',
                'Kothi',
                'Independent Floor',
                'Serviced Apartment',
                'PG / Paying Guest',
                'Co-living Space',
                'Vacation Home',
                'Holiday Home',
            ],
            'Commercial' => [
                'Office Space',
                'IT Office',
                'Co-working Space',
                'Shop',
                'Showroom',
                'Retail Space',
                'Commercial Complex',
                'Business Centre',
                'Commercial Plot',
                'Commercial Land',
                'Warehouse',
                'Godown',
                'Industrial Shed',
                'Factory',
                'Manufacturing Unit',
                'Cold Storage',
                'Logistics Park',
                'Commercial Building',
                'Hotel',
                'Motel',
                'Guest House',
                'Restaurant Space',
                'Food Court Space',
                'Banquet Hall',
                'Cinema',
                'Mall Shop',
                'Clinic',
                'Hospital Space',
                'Diagnostic Centre',
                'Educational Institute',
                'School Building',
                'College Building',
            ],
            'Industrial' => [
                'Industrial Plot',
                'Factory',
                'Manufacturing Plant',
                'Warehouse',
                'Industrial Shed',
                'Logistics Warehouse',
                'Cold Storage',
                'Distribution Centre',
                'Industrial Building',
            ],
            'Agricultural' => [
                'Agricultural Land',
                'Farm Land',
                'Orchard',
                'Plantation',
                'Dairy Farm',
                'Poultry Farm',
                'Fish Farm',
            ],
            'Mixed Use' => [
                'Mixed Use Building',
                'Residential + Commercial Building',
                'Retail + Office',
                'Commercial + Residential Plot',
            ],
            'Luxury' => [
                'Luxury Villa',
                'Luxury Apartment',
                'Luxury Penthouse',
                'Beach House',
                'Golf Villa',
                'Smart Home',
                'Mansion',
            ],
            'Investment Properties' => [
                'Pre-Leased Property',
                'Rental Property',
                'Income Property',
                'REIT Property',
                'Auction Property',
            ],
            'Land Categories' => [
                'Residential Plot',
                'Commercial Plot',
                'Industrial Plot',
                'Agricultural Land',
                'Institutional Land',
                'Farm Land',
                'NA (Non-Agricultural) Land',
                'Freehold Land',
                'Leasehold Land',
            ],
            'Builder Projects' => [
                'New Launch',
                'Under Construction',
                'Ready to Move',
                'Resale Property',
                'Redevelopment Project',
            ],
            'Special Purpose' => [
                'Petrol Pump Site',
                'Telecom Tower Site',
                'Resort',
                'Warehouse Park',
                'Data Centre',
                'Solar Farm',
                'Wind Farm',
                'Religious Property',
                'Marriage Hall',
                'Community Hall',
            ],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::firstOrCreate([
                'name' => $parentName,
                'parent_id' => null,
            ], [
                'status' => true,
            ]);

            foreach ($children as $childName) {
                Category::firstOrCreate([
                    'name' => $childName,
                    'parent_id' => $parent->id,
                ], [
                    'status' => true,
                ]);
            }
        }
    }
}
