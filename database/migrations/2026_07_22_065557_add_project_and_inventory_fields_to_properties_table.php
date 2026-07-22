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
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('project_id')
                  ->nullable()
                  ->constrained('projects')
                  ->nullOnDelete();
            $table->string('tower')->nullable();
            $table->string('floor')->nullable();
            $table->string('unit_number')->nullable();
            $table->enum('inventory_status', ['available', 'sold', 'reserved', 'blocked'])->default('available');
            $table->string('facing')->nullable();
            $table->unsignedTinyInteger('parking_slots')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn([
                'project_id',
                'tower',
                'floor',
                'unit_number',
                'inventory_status',
                'facing',
                'parking_slots'
            ]);
        });
    }
};
