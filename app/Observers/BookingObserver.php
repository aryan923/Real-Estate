<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Enquiry;

class BookingObserver
{
    /**
     * Handle the Booking "saved" event.
     */
    public function saved(Booking $booking): void
    {
        // 1. Sync associated property unit status
        $property = $booking->property;
        if ($property) {
            $newStatus = match ($booking->status) {
                'confirmed' => 'sold',
                'pending_approval' => 'reserved',
                'cancelled' => 'available',
                default => 'available'
            };

            if ($property->inventory_status !== $newStatus) {
                $property->inventory_status = $newStatus;
                $property->save();
            }
        }

        // 2. Sync associated enquiry/lead to won if confirmed or pending approval
        if (in_array($booking->status, ['confirmed', 'pending_approval'])) {
            $enquiry = Enquiry::where('email', $booking->customer?->email)
                ->orWhere('phone', $booking->customer?->phone)
                ->first();

            if ($enquiry && $enquiry->status !== 'won') {
                $enquiry->status = 'won';
                $enquiry->lead_score = 100; // instant conversion!
                $enquiry->saveQuietly();
            }
        }
    }
}
