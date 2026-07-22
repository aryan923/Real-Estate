<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Customer;
use App\Models\Booking;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Customer::where('name', 'Rahul Sharma')->first();
        $booking = Booking::first();

        if ($customer) {
            Document::create([
                'documentable_type' => Customer::class,
                'documentable_id' => $customer->id,
                'file_name' => 'rahul_sharma_pan_card.pdf',
                'file_path' => '/storage/documents/mock_pan.pdf',
                'file_size' => 245000,
                'category' => 'PAN',
            ]);

            Document::create([
                'documentable_type' => Customer::class,
                'documentable_id' => $customer->id,
                'file_name' => 'rahul_sharma_aadhaar_card.pdf',
                'file_path' => '/storage/documents/mock_aadhaar.pdf',
                'file_size' => 480000,
                'category' => 'Aadhaar',
            ]);
        }

        if ($booking) {
            Document::create([
                'documentable_type' => Booking::class,
                'documentable_id' => $booking->id,
                'file_name' => 'signed_purchase_agreement_' . $booking->booking_no . '.pdf',
                'file_path' => '/storage/documents/mock_agreement.pdf',
                'file_size' => 1250000,
                'category' => 'Agreement',
            ]);
        }
    }
}
