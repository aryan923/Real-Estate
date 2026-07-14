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

        $parentImages = [
            'Residential' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'Commercial' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
            'Land Categories' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
            'Builder Projects' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80',
            'Special Purpose' => 'https://images.unsplash.com/photo-1581094288338-2314dddb7ecc?auto=format&fit=crop&w=1200&q=80',
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::firstOrCreate([
                'name' => $parentName,
                'parent_id' => null,
            ], [
                'image' => $parentImages[$parentName] ?? null,
                'status' => true,
                'is_featured' => true,
            ]);

            foreach ($children as $childName) {
                Category::firstOrCreate([
                    'name' => $childName,
                    'parent_id' => $parent->id,
                ], [
                    'status' => true,
                    'is_featured' => false,
                ]);
            }
        }
    }
}
