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
                'view_dashboard',
            ],
            'Properties' => [
                'view_properties',
                'create_properties',
                'edit_properties',
                'delete_properties',
            ],
            'Manage Leads' => [
                'view_enquiries',
                'create_enquiries',
                'edit_enquiries',
                'delete_enquiries',
                'view_won',
                'view_all_leads',
            ],
            'Manage Testimonials' => [
                'view_testimonials',
                'create_testimonials',
                'edit_testimonials',
                'delete_testimonials',
            ],
            'Manage Blog' => [
                'view_posts',
                'create_posts',
                'edit_posts',
                'delete_posts',
            ],
            'Manage Pages' => [
                'view_pages',
                'create_pages',
                'edit_pages',
                'delete_pages',
            ],
            'Manage Comments' => [
                'view_comments',
                'create_comments',
                'edit_comments',
                'delete_comments',
            ],
            'Manage Agents' => [
                'view_agents',
                'create_agents',
                'edit_agents',
                'delete_agents',
            ],
            'User Management' => [
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
            ],
            'Roles & Permissions' => [
                'view_roles',
                'create_roles',
                'edit_roles',
                'delete_roles',
            ],
            'General Setting' => [
                'view_settings',
                'update_settings',
            ],
            'Categories' => [
                'view_categories',
                'create_categories',
                'edit_categories',
                'delete_categories',
            ],
            'Features' => [
                'view_features',
                'create_features',
                'edit_features',
                'delete_features',
            ],
            'Cities' => [
                'view_cities',
                'create_cities',
                'edit_cities',
                'delete_cities',
            ],
            'Currencies' => [
                'view_currencies',
                'create_currencies',
                'edit_currencies',
                'delete_currencies',
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


        $allSeederPermissions = [];
        foreach ($permissionsByGroup as $groupName => $permissionNames) {
            $allSeederPermissions = array_merge($allSeederPermissions, $permissionNames);
        }

        Permission::query()
            ->whereNotIn('name', $allSeederPermissions)
            ->delete();

        $adminRole = Role::findOrCreate('Admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        $agentRole = Role::findOrCreate('Agent', 'web');
        $agentRole->syncPermissions([
            'view_dashboard',
            'view_enquiries',
            'create_enquiries',
            'edit_enquiries',
        ]);

        User::query()
            ->where('is_admin', true)
            ->get()
            ->each(function (User $user) use ($adminRole): void {
                if (! $user->hasRole($adminRole)) {
                    $user->assignRole($adminRole);
                }
            });

        User::has('agentMeta')
            ->get()
            ->each(function (User $user) use ($agentRole): void {
                if (! $user->hasRole($agentRole)) {
                    $user->assignRole($agentRole);
                }
            });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
