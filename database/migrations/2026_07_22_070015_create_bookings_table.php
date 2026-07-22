<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete();
            $table->foreignId('property_id')
                  ->constrained('properties')
                  ->cascadeOnDelete();
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->cascadeOnDelete();
            $table->decimal('booking_amount', 12, 2);
            $table->decimal('total_agreement_value', 12, 2);
            $table->date('booking_date');
            $table->enum('status', ['pending_approval', 'confirmed', 'cancelled'])->default('pending_approval');
            $table->string('agreement_file_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
