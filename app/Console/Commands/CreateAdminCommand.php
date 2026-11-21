<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or promote a user to Super Administrator';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('--- PropDesk Admin Account Creator ---');

        $name = $this->option('name') ?: $this->ask('Admin Full Name', 'System Admin');
        $email = $this->option('email') ?: $this->ask('Admin Email Address');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address provided.');
            return Command::FAILURE;
        }

        $existingUser = User::where('email', $email)->first();

        $password = $this->option('password') ?: $this->secret('Enter Password (hidden)');
        if (empty($password)) {
            $this->error('Password cannot be empty.');
            return Command::FAILURE;
        }

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return Command::FAILURE;
        }

        if ($existingUser) {
            if (!$this->confirm("User '{$email}' already exists. Update password and promote to Admin?", true)) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }

            $existingUser->password = Hash::make($password);
            $existingUser->is_admin = true;
            $existingUser->status = 'active';
            $existingUser->save();
            $user = $existingUser;
        } else {
            $phone = $this->ask('Phone Number (Optional)', '0000000000');
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make($password),
                'is_admin' => true,
                'status' => 'active',
                'remember_token' => Str::random(10),
            ]);
        }

        // Assign Spatie Admin Role
        if (class_exists(Role::class)) {
            $adminRole = Role::findOrCreate('Admin', 'web');
            if (!$user->hasRole($adminRole)) {
                $user->assignRole($adminRole);
            }
        }

        $this->info("✓ Administrator '{$email}' configured successfully!");
        return Command::SUCCESS;
    }
}
