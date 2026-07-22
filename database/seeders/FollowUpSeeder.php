<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enquiry;
use App\Models\FollowUp;
use App\Models\User;

class FollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user
        $user = User::first();
        if (!$user) {
            return;
        }

        // 1. Create Enquiries (Leads)
        $enq1 = Enquiry::create([
            'type' => 'info',
            'enq_for' => 'sale',
            'name' => 'Ananya Sen',
            'email' => 'ananya.sen@example.com',
            'phone' => '+91 99999 88888',
            'message' => 'Interested in buying a 3BHK flat at Green Heights.',
            'status' => 'new',
            'lead_score' => 85
        ]);

        $enq2 = Enquiry::create([
            'type' => 'info',
            'enq_for' => 'sale',
            'name' => 'Rohit Verma',
            'email' => 'rohit.verma@example.com',
            'phone' => '+91 88888 77777',
            'message' => 'Looking for details about construction timelines.',
            'status' => 'new',
            'lead_score' => 45
        ]);

        // 2. Create Follow-up records
        FollowUp::create([
            'enquiry_id' => $enq1->id,
            'scheduled_at' => now()->addDay()->setHour(10)->setMinute(0)->setSecond(0),
            'reminder_before_minutes' => 15,
            'status' => 'pending',
            'created_by' => $user->id,
        ]);

        FollowUp::create([
            'enquiry_id' => $enq2->id,
            'scheduled_at' => now()->addDays(2)->setHour(14)->setMinute(30)->setSecond(0),
            'reminder_before_minutes' => 30,
            'status' => 'pending',
            'created_by' => $user->id,
        ]);
    }
}
