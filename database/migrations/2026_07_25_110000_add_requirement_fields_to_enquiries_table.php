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
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('budget_type')->nullable()->after('budget');
            $table->string('payment_mode')->nullable()->after('budget_type');
            $table->boolean('loan_required')->default(false)->after('payment_mode');
            $table->decimal('loan_amount', 15, 2)->nullable()->after('loan_required');
            $table->decimal('down_payment', 15, 2)->nullable()->after('loan_amount');
            $table->string('preferred_type')->nullable()->after('down_payment');
            $table->string('bhk')->nullable()->after('preferred_type');
            $table->string('carpet_area')->nullable()->after('bhk');
            $table->string('floor_preference')->nullable()->after('carpet_area');
            $table->string('furnishing')->nullable()->after('floor_preference');
            $table->json('amenities')->nullable()->after('furnishing');
            $table->text('other_preferences')->nullable()->after('amenities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn([
                'budget_type',
                'payment_mode',
                'loan_required',
                'loan_amount',
                'down_payment',
                'preferred_type',
                'bhk',
                'carpet_area',
                'floor_preference',
                'furnishing',
                'amenities',
                'other_preferences',
            ]);
        });
    }
};
