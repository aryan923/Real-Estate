<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()
            ->withCount('permissions')
            ->orderBy('name')
            ->get();

        return view('admin.pages.roles-permissions', [
            'roles' => $roles,
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.role-permissions-create', [
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function edit(Role $role): View
    {
        return view('admin.pages.role-permissions-edit', [
            'editingRole' => $role->load('permissions'),
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRole($request);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(array_map('intval', $validated['permissions'] ?? []));

        return redirect()
            ->route('admin.roles-permissions')
            ->with('status', 'Role created successfully.');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $this->validateRole($request, $role);

        $role->update([
            'name' => $validated['name'],
        ]);

        $role->syncPermissions(array_map('intval', $validated['permissions'] ?? []));

        return redirect()
            ->route('admin.roles-permissions.edit', $role)
            ->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()
            ->route('admin.roles-permissions')
            ->with('status', 'Role deleted successfully.');
    }

    private function permissionGroups()
    {
        return Permission::query()
            ->orderByRaw('COALESCE(group_name, name) asc')
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => $permission->group_name ?: 'Other');
    }

    private function validateRole(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(config('permission.table_names.roles'), 'name')->ignore($role?->id),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'integer',
                Rule::exists(config('permission.table_names.permissions'), 'id'),
            ],
        ]);
    }
}
