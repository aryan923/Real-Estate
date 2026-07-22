<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enquiry;
use App\Models\Project;
use App\Models\SiteVisit;
use App\Models\User;

class SiteVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $enq = Enquiry::where('name', 'Ananya Sen')->first();
        $proj = Project::where('slug', 'green-heights')->first();

        if ($user && $enq && $proj) {
            SiteVisit::create([
                'enquiry_id' => $enq->id,
                'project_id' => $proj->id,
                'scheduled_at' => now()->addDays(3)->setHour(11)->setMinute(0)->setSecond(0),
                'pickup_required' => true,
                'pickup_address' => 'Malviya Nagar, New Delhi',
                'driver_name' => 'Ramesh Kumar',
                'vehicle_number' => 'DL 3C AY 4321',
                'assigned_executive_id' => $user->id,
                'status' => 'scheduled',
            ]);
        }
    }
}
