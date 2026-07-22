<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Property;
use App\Models\Project;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Customer::where('name', 'Rahul Sharma')->first();
        $project = Project::where('slug', 'green-heights')->first();

        if ($customer && $project) {
            // Find an active property belonging to Green Heights project that we can book
            // Let's pick the first property under Green Heights
            $property = Property::where('project_id', $project->id)->first();

            if ($property) {
                // Create confirmed booking
                Booking::create([
                    'booking_no' => 'BK-' . date('Ymd') . '-' . rand(1000, 9999),
                    'customer_id' => $customer->id,
                    'property_id' => $property->id,
                    'project_id' => $project->id,
                    'booking_amount' => 1000000.00,
                    'total_agreement_value' => 7500000.00,
                    'booking_date' => now()->subDays(5)->format('Y-m-d'),
                    'status' => 'confirmed',
                    'notes' => 'Standard 10% token booking fee received. Remaining payments assigned to slab construction milestones.',
                ]);

                // Update property inventory status to sold
                $property->update(['inventory_status' => 'sold']);
            }
        }
    }
}
