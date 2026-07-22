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
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')
                  ->constrained('enquiries')
                  ->cascadeOnDelete();
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->boolean('pickup_required')->default(false);
            $table->string('pickup_address')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->foreignId('assigned_executive_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->enum('status', ['scheduled', 'visited', 'not_visited', 'rescheduled', 'cancelled'])->default('scheduled');
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
