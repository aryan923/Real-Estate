<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD');

        // Security check: Never seed default weak password in production
        if (app()->isProduction() && empty($password)) {
            $this->command?->warn('ADMIN_PASSWORD is not set in .env. Skipping default admin seeding in production for security.');
            $this->command?->info('Run "php artisan make:admin" to create a secure administrator account.');
            return;
        }

        $password = $password ?: 'password';

        // Use firstOrCreate to prevent overwriting existing passwords on subsequent seeder runs
        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Admin User'),
                'phone' => env('ADMIN_PHONE', '0000000000'),
                'password' => Hash::make($password),
                'remember_token' => Str::random(10),
                'is_admin' => true,
                'status' => 'active',
            ]
        );

        // Assign Spatie Admin Role
        if (class_exists(Role::class)) {
            $adminRole = Role::findOrCreate('Admin', 'web');
            if (!$admin->hasRole($adminRole)) {
                $admin->assignRole($adminRole);
            }
        }
    }
}
