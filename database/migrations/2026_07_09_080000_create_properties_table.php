<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('price')->nullable();
            $table->string('price_type')->nullable();
            $table->string('price_info')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('featured')->default(false);
            $table->string('property_code')->nullable();
            $table->enum('property_purpose', ['sale', 'rent', 'lease'])->nullable();
            $table->enum('listing_type', ['owner', 'agent', 'builder'])->nullable();
            $table->string('RERA_no')->nullable();
            $table->string('builder_developer')->nullable();
            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->unsignedSmallInteger('rooms')->nullable();
            $table->string('size')->nullable();
            $table->string('garage_size')->nullable();
            $table->unsignedSmallInteger('year_built')->nullable();
            $table->date('available_from')->nullable();
            $table->string('exterior_material')->nullable();
            $table->string('structure_type')->nullable();
            $table->unsignedSmallInteger('floors')->nullable();
            $table->string('video_url')->nullable();
            $table->string('floor_plan_url')->nullable();
            $table->decimal('map_latitude', 10, 7)->nullable();
            $table->decimal('map_longitude', 10, 7)->nullable();
            $table->string('address_line')->nullable();
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->string('locality')->nullable();
            $table->unsignedMediumInteger('state_id')->nullable();
            $table->string('zip')->nullable();
            $table->unsignedMediumInteger('country_id')->nullable();
            $table->softDeletes();
            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
            $table->foreign('state_id')->references('id')->on('states')->nullOnDelete();
            $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
