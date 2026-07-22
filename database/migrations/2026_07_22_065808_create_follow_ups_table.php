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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')
                  ->constrained('enquiries')
                  ->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->unsignedInteger('reminder_before_minutes')->default(15);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('call_notes')->nullable();
            $table->enum('outcome', ['interested', 'need_more_time', 'not_interested', 'booked_visit', 'no_response'])->nullable();
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
