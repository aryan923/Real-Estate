<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Project;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proj1 = Project::where('slug', 'green-heights')->first();
        $proj2 = Project::where('slug', 'royal-residency')->first();

        Customer::create([
            'name' => 'Rahul Sharma',
            'email' => 'rahul.sharma@example.com',
            'phone' => '+91 98765 43210',
            'pan_number' => 'ABCDE1234F',
            'aadhaar_number' => '1234-5678-9012',
            'occupation' => 'Software Engineer',
            'budget_range' => '60 Lakhs - 1 Crore',
            'family_members' => [
                ['name' => 'Priya Sharma', 'relation' => 'Spouse'],
                ['name' => 'Aarav Sharma', 'relation' => 'Son']
            ],
            'interested_projects' => $proj1 ? [$proj1->id] : []
        ]);

        Customer::create([
            'name' => 'Vikram Adityan',
            'email' => 'vikram.adityan@example.com',
            'phone' => '+91 98989 89898',
            'pan_number' => 'XYZWV5678K',
            'aadhaar_number' => '9876-5432-1098',
            'occupation' => 'Business Owner',
            'budget_range' => 'Above 2 Crore',
            'family_members' => [
                ['name' => 'Anjali Adityan', 'relation' => 'Spouse']
            ],
            'interested_projects' => $proj2 ? [$proj2->id] : []
        ]);
    }
}
