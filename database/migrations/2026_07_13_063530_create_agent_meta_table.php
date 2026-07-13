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
        Schema::create('agent_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('company')->nullable();
            $table->string('member_of')->nullable();
            $table->string('address')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('vimeo')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('about_me')->nullable();
            $table->text('specialties')->nullable();
            $table->text('experience_details')->nullable();
            $table->string('reach_time')->nullable();
            $table->string('awards')->nullable();
            $table->string('mls_id')->nullable();
            $table->string('languages')->nullable();
            $table->string('experience_years')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_meta');
    }
};
