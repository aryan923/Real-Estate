<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionsByGroup = [
            'Dashboard' => [
                'view dashboard',
            ],
            'User Management' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
            ],
            'Roles & Permissions' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
            'General Setting' => [
                'view settings',
                'update settings',
            ],
        ];

        foreach ($permissionsByGroup as $groupName => $permissionNames) {
            foreach ($permissionNames as $permissionName) {
                Permission::query()->updateOrCreate(
                    [
                        'name' => $permissionName,
                        'guard_name' => 'web',
                    ],
                    [
                        'group_name' => $groupName,
                    ]
                );
            }
        }

        $adminRole = Role::findOrCreate('Admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        User::query()
            ->where('is_admin', true)
            ->get()
            ->each(function (User $user) use ($adminRole): void {
                if (! $user->hasRole($adminRole)) {
                    $user->assignRole($adminRole);
                }
            });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
