<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->foreignId('agent_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
        });

        DB::statement("ALTER TABLE enquiries MODIFY COLUMN status ENUM('draft', 'new', 'contacted', 'interested', 'follow_up', 'site_visit', 'negotiation', 'won', 'lost') NOT NULL DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE enquiries MODIFY COLUMN status ENUM('new', 'contacted', 'interested', 'follow_up', 'site_visit', 'negotiation', 'won', 'lost') NOT NULL DEFAULT 'new'");

        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};
