<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE enquiries MODIFY COLUMN type ENUM('contact', 'info', 'tour', 'agent', 'property_submit') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE enquiries MODIFY COLUMN type ENUM('contact', 'info', 'tour', 'agent') NOT NULL");
    }
};
