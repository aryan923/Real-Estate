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
            'Projects' => [
                'view_projects',
                'create_projects',
                'edit_projects',
                'delete_projects',
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
            'Customers' => [
                'view_customers',
                'create_customers',
                'edit_customers',
                'delete_customers',
            ],
            'Follow-ups' => [
                'view_follow_ups',
                'create_follow_ups',
                'edit_follow_ups',
                'delete_follow_ups',
            ],
            'Site Visits' => [
                'view_site_visits',
                'create_site_visits',
                'edit_site_visits',
                'delete_site_visits',
            ],
            'Bookings' => [
                'view_bookings',
                'create_bookings',
                'edit_bookings',
                'delete_bookings',
            ],
            'Payments' => [
                'view_payments',
                'create_payments',
                'edit_payments',
                'delete_payments',
            ],
            'Documents' => [
                'view_documents',
                'create_documents',
                'delete_documents',
            ],
            'Reports' => [
                'view_reports',
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

        // Setup Roles
        $adminRole = Role::findOrCreate('Admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::findOrCreate('Sales Manager', 'web');
        $managerRole->syncPermissions([
            'view_dashboard',
            'view_projects', 'create_projects', 'edit_projects',
            'view_properties', 'create_properties', 'edit_properties',
            'view_enquiries', 'create_enquiries', 'edit_enquiries', 'delete_enquiries', 'view_all_leads', 'view_won',
            'view_customers', 'create_customers', 'edit_customers',
            'view_follow_ups', 'create_follow_ups', 'edit_follow_ups',
            'view_site_visits', 'create_site_visits', 'edit_site_visits',
            'view_bookings', 'create_bookings', 'edit_bookings',
            'view_payments', 'view_documents', 'view_reports',
            'view_testimonials', 'view_posts', 'view_comments', 'view_agents',
        ]);

        $executiveRole = Role::findOrCreate('Sales Executive', 'web');
        $executiveRole->syncPermissions([
            'view_dashboard',
            'view_projects',
            'view_properties',
            'view_enquiries', 'create_enquiries', 'edit_enquiries',
            'view_customers', 'create_customers', 'edit_customers',
            'view_follow_ups', 'create_follow_ups', 'edit_follow_ups',
            'view_site_visits', 'create_site_visits', 'edit_site_visits',
            'view_bookings', 'create_bookings',
            'view_documents',
        ]);

        // Keep 'Agent' role alias mapped for compatibility
        $agentRole = Role::findOrCreate('Agent', 'web');
        $agentRole->syncPermissions($executiveRole->permissions);

        $accountsRole = Role::findOrCreate('Accounts', 'web');
        $accountsRole->syncPermissions([
            'view_dashboard',
            'view_bookings',
            'view_payments', 'create_payments', 'edit_payments',
            'view_documents', 'view_reports',
        ]);

        $receptionRole = Role::findOrCreate('Reception', 'web');
        $receptionRole->syncPermissions([
            'view_dashboard',
            'view_enquiries', 'create_enquiries',
            'view_site_visits',
        ]);

        $marketingRole = Role::findOrCreate('Marketing', 'web');
        $marketingRole->syncPermissions([
            'view_dashboard',
            'view_enquiries', 'create_enquiries', 'view_all_leads',
            'view_reports',
            'view_posts', 'create_posts', 'edit_posts', 'delete_posts',
            'view_testimonials',
        ]);

        // Assign Admin role to Admin users
        User::query()
            ->where('is_admin', true)
            ->get()
            ->each(function (User $user) use ($adminRole): void {
                if (! $user->hasRole($adminRole)) {
                    $user->assignRole($adminRole);
                }
            });

        // Assign Sales Executive (Agent) role to agent users
        User::has('agentMeta')
            ->get()
            ->each(function (User $user) use ($executiveRole): void {
                if (! $user->hasRole($executiveRole)) {
                    $user->assignRole($executiveRole);
                }
            });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
