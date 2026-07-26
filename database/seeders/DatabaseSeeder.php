<?php

namespace Database\Seeders;

use Database\Seeders\AdminUserSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            RolePermissionSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            FeatureSeeder::class,
            CategorySeeder::class,
            TestimonialSeeder::class,
            AgentSeeder::class,
            PostSeeder::class,
            CurrencySeeder::class,
            IndianPropertySeeder::class,
            PageSeeder::class,
            ProjectSeeder::class,
            CustomerSeeder::class,
            FollowUpSeeder::class,
            SiteVisitSeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
