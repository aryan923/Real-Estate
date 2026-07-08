<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ImageuploadHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.user-management', [
            'roles' => $this->roles(),
        ]);
    }

    public function data(Request $request)
    {
        $query = User::query()->with('roles');

        if ($request->filled('role_filter') && $request->string('role_filter')->toString() !== 'all') {
            $roleFilter = $request->string('role_filter')->toString();

            if ($roleFilter === 'no-role') {
                $query->doesntHave('roles');
            } else {
                $query->whereHas('roles', function ($roleQuery) use ($roleFilter): void {
                    $roleQuery->where('name', $roleFilter);
                });
            }
        }

        if ($request->filled('admin_filter') && in_array($request->string('admin_filter')->toString(), ['yes', 'no'], true)) {
            $query->where('is_admin', $request->string('admin_filter')->toString() === 'yes');
        }

        return DataTables::eloquent($query)
            ->addColumn('photo', function (User $user): string {
                if (! $user->profile_pic) {
                    return '<div class="flex h-10 w-10 items-center justify-center rounded-full border border-dashed border-gray-300 text-xs font-semibold text-gray-400 dark:border-gray-700 dark:text-gray-500">N/A</div>';
                }

                $imageUrl = e(ImageuploadHelper::getUploadedFileUrl($user->profile_pic));
                $alt = e($user->name . ' profile picture');

                return '<img src="' . $imageUrl . '" alt="' . $alt . '" class="h-10 w-10 rounded-full border border-gray-200 object-cover dark:border-gray-700" />';
            })
            ->addColumn('role', fn (User $user): string => $user->roles->first()?->name ?? 'No role')
            ->addColumn('admin_status', fn (User $user): string => $user->is_admin ? 'Yes' : 'No')
            ->addColumn('action', function (User $user): string {
                return view('admin.pages.partials.user-management-action-buttons', [
                    'user' => $user,
                ])->render();
            })
            ->filterColumn('role', function ($query, $keyword): void {
                $query->whereHas('roles', function ($roleQuery) use ($keyword): void {
                    $roleQuery->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->rawColumns(['photo', 'action'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.pages.user-management-create', [
            'roles' => $this->roles(),
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.pages.user-management-edit', [
            'editingUser' => $user->load('roles'),
            'roles' => $this->roles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUser($request);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'is_admin' => (bool) ($validated['is_admin'] ?? false),
            'profile_pic' => $this->storeProfilePicture($request),
        ]);

        $this->syncRole($user, $validated['role_id'] ?? null);

        return redirect()
            ->route('admin.user-management')
            ->with('status', 'User created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validateUser($request, $user);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_admin' => (bool) ($validated['is_admin'] ?? false),
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $newProfilePicture = $this->storeProfilePicture($request);

        if ($newProfilePicture) {
            $this->deleteStoredProfilePicture($user->profile_pic);
            $payload['profile_pic'] = $newProfilePicture;
        }

        $user->update($payload);

        $this->syncRole($user, $validated['role_id'] ?? null);

        return redirect()
            ->route('admin.user-management.edit', $user)
            ->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('admin.user-management')
                ->with('status', 'You cannot delete your own account.');
        }

        $this->deleteStoredProfilePicture($user->profile_pic);

        $user->delete();

        return redirect()
            ->route('admin.user-management')
            ->with('status', 'User deleted successfully.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $passwordRules = ['nullable', 'string', 'min:8'];

        if ($user === null) {
            $passwordRules = ['required', 'string', 'min:8'];
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'phone')->ignore($user?->id),
            ],
            'password' => $passwordRules,
            'profile_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'role_id' => ['nullable', 'integer', Rule::exists(config('permission.table_names.roles'), 'id')],
            'is_admin' => ['nullable', 'boolean'],
        ]);
    }

    private function roles()
    {
        return Role::query()->orderBy('name')->get();
    }

    private function syncRole(User $user, ?int $roleId): void
    {
        if (! $roleId) {
            $user->syncRoles([]);

            return;
        }

        $role = Role::query()->find($roleId);

        if ($role) {
            $user->syncRoles([$role->name]);
        }
    }

    private function storeProfilePicture(Request $request): ?string
    {
        if (! $request->hasFile('profile_pic')) {
            return null;
        }

        return ImageuploadHelper::upload($request->file('profile_pic'), 'users');
    }

    private function deleteStoredProfilePicture(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://', '/'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
