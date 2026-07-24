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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'contact', // Contact Me
                'info',    // Request Info
                'tour',    // Schedule Tour
                'agent'    // Contact Agent
            ]);
            $table->enum('enq_for', [
                'rent',
                'sale',
                'lease',
                'contact'
            ])->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->foreignId('property_id')
                  ->nullable()
                  ->constrained('properties')
                  ->nullOnDelete();
            $table->string('lead_source')->nullable()->default('Website');
            $table->string('location')->nullable();
            $table->string('budget')->nullable();
            $table->string('tags')->nullable();
            // Tour specific
            $table->dateTime('tour_schedule_time')->nullable();
            $table->enum('tour_type', [
                'in_person',
                'video_chat'
            ])->nullable();
            // CRM
            $table->enum('status', [
                'new',
                'contacted',
                'interested',
                'follow_up',
                'site_visit',
                'negotiation',
                'won',
                'lost'
            ])->default('new');
            $table->foreignId('action_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->dateTime('performed_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
