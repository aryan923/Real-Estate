<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $booking = Booking::first();

        if ($booking) {
            $value = $booking->total_agreement_value;
            $date = $booking->booking_date;

            $slabs = [
                ['stage' => 'token', 'pct' => 0.10, 'days' => 0, 'status' => 'paid', 'method' => 'Bank Transfer'],
                ['stage' => 'down_payment', 'pct' => 0.15, 'days' => 30, 'status' => 'paid', 'method' => 'Cheque'],
                ['stage' => 'slab_1', 'pct' => 0.25, 'days' => 90, 'status' => 'pending', 'method' => null],
                ['stage' => 'slab_2', 'pct' => 0.25, 'days' => 180, 'status' => 'pending', 'method' => null],
                ['stage' => 'possession', 'pct' => 0.25, 'days' => 360, 'status' => 'pending', 'method' => null],
            ];

            foreach ($slabs as $idx => $slab) {
                $dueAmount = $value * $slab['pct'];
                $paidAmount = $slab['status'] === 'paid' ? $dueAmount : 0.00;
                $paidDate = $slab['status'] === 'paid' ? $date->copy()->addDays($slab['days']) : null;
                $receiptNo = $slab['status'] === 'paid' ? 'REC-' . date('Ymd') . '-' . rand(1000, 9999) : null;

                Payment::create([
                    'booking_id' => $booking->id,
                    'installment_stage' => $slab['stage'],
                    'amount_due' => $dueAmount,
                    'amount_paid' => $paidAmount,
                    'due_date' => $date->copy()->addDays($slab['days']),
                    'paid_date' => $paidDate,
                    'status' => $slab['status'],
                    'receipt_no' => $receiptNo,
                    'payment_method' => $slab['method'],
                ]);
            }
        }
    }
}
