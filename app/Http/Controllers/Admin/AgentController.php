<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ImageuploadHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgentMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AgentController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.agents.index');
    }

    public function data(Request $request)
    {
        $query = User::query()->has('agentMeta')->with('agentMeta');

        if ($request->filled('is_featured')) {
            $isFeatured = $request->input('is_featured');
            $query->whereHas('agentMeta', function ($q) use ($isFeatured) {
                $q->where('is_featured', $isFeatured === 'yes');
            });
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
            ->addColumn('title', fn (User $user): string => $user->agentMeta?->title ?? 'N/A')
            ->addColumn('company', fn (User $user): string => $user->agentMeta?->company ?? 'N/A')
            ->addColumn('featured_label', fn (User $user): string => $user->agentMeta?->is_featured ? 'Yes' : 'No')
            ->addColumn('status_label', fn (User $user): string => $user->status === 'active' ? 'Active' : 'Inactive')
            ->addColumn('action', function (User $user): string {
                return '<div class="flex flex-wrap gap-2">'
                    . '<a href="' . route('admin.agents.show', $user) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">View</a>'
                    . '<a href="' . route('admin.agents.edit', $user) . '" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-white/5">Edit</a>'
                    . '<form method="POST" action="' . route('admin.agents.destroy', $user) . '" class="inline-block" data-confirm="Delete this agent?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-600 dark:bg-red-500/10 dark:text-red-200 dark:hover:bg-red-500/20">Delete</button>'
                    . '</form>'
                    . '</div>';
            })
            ->rawColumns(['photo', 'action'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.pages.agents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAgent($request);

        $user = DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => bcrypt($validated['password']),
                'profile_pic' => $this->storeProfilePicture($request),
                'is_admin' => false,
                'status' => $validated['status'] ?? 'active',
            ]);

            // Create agent meta
            AgentMeta::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'company' => $validated['company'],
                'member_of' => $validated['member_of'],
                'address' => $validated['address'],
                'facebook' => $validated['facebook'],
                'twitter' => $validated['twitter'],
                'linkedin' => $validated['linkedin'],
                'instagram' => $validated['instagram'],
                'youtube' => $validated['youtube'],
                'vimeo' => $validated['vimeo'],
                'whatsapp' => $validated['whatsapp'],
                'about_me' => $validated['about_me'],
                'specialties' => $validated['specialties'],
                'experience_details' => $validated['experience_details'],
                'reach_time' => $validated['reach_time'],
                'awards' => $validated['awards'],
                'mls_id' => $validated['mls_id'],
                'languages' => $validated['languages'],
                'experience_years' => $validated['experience_years'],
                'is_featured' => $validated['is_featured'] ?? false,
            ]);

            $user->assignRole('Agent');

            return $user;
        });

        return redirect()
            ->route('admin.agents')
            ->with('status', "Agent '{$user->name}' created successfully.");
    }

    public function show(User $agent): View
    {
        $user = $agent;
        $user->load('agentMeta');

        $enquiries = \App\Models\Enquiry::where('agent_id', $user->id)
            ->with(['property'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.pages.agents.show', compact('user', 'enquiries'));
    }

    public function edit(User $agent): View
    {
        $user = $agent;
        $user->load('agentMeta');
        return view('admin.pages.agents.edit', compact('user'));
    }

    public function update(Request $request, User $agent): RedirectResponse
    {
        $user = $agent;
        $validated = $this->validateAgent($request, $user);

        DB::transaction(function () use ($validated, $request, $user) {
            // Update user
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'status' => $validated['status'] ?? 'active',
            ];

            if ($request->filled('password')) {
                $userData['password'] = bcrypt($validated['password']);
            }

            if ($request->hasFile('profile_pic')) {
                $this->deleteStoredProfilePicture($user->profile_pic);
                $userData['profile_pic'] = $this->storeProfilePicture($request);
            }

            $user->update($userData);

            // Update or create agent meta
            $user->agentMeta()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'title' => $validated['title'],
                    'company' => $validated['company'],
                    'member_of' => $validated['member_of'],
                    'address' => $validated['address'],
                    'facebook' => $validated['facebook'],
                    'twitter' => $validated['twitter'],
                    'linkedin' => $validated['linkedin'],
                    'instagram' => $validated['instagram'],
                    'youtube' => $validated['youtube'],
                    'vimeo' => $validated['vimeo'],
                    'whatsapp' => $validated['whatsapp'],
                    'about_me' => $validated['about_me'],
                    'specialties' => $validated['specialties'],
                    'experience_details' => $validated['experience_details'],
                    'reach_time' => $validated['reach_time'],
                    'awards' => $validated['awards'],
                    'mls_id' => $validated['mls_id'],
                    'languages' => $validated['languages'],
                    'experience_years' => $validated['experience_years'],
                    'is_featured' => $validated['is_featured'] ?? false,
                ]
            );
        });

        return redirect()
            ->route('admin.agents')
            ->with('status', "Agent '{$user->name}' updated successfully.");
    }

    public function destroy(User $agent): RedirectResponse
    {
        $user = $agent;
        $name = $user->name;

        DB::transaction(function () use ($user) {
            $this->deleteStoredProfilePicture($user->profile_pic);
            $user->delete(); // cascading delete will handle agent_meta
        });

        return redirect()
            ->route('admin.agents')
            ->with('status', "Agent '{$name}' deleted successfully.");
    }

    private function validateAgent(Request $request, ?User $user = null): array
    {
        $passwordRules = ['nullable', 'string', 'min:8'];
        if ($user === null) {
            $passwordRules = ['required', 'string', 'min:8'];
        }

        return $request->validate([
            // User validations
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

            // AgentMeta validations
            'title' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'member_of' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'vimeo' => ['nullable', 'url', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'about_me' => ['nullable', 'string'],
            'specialties' => ['nullable', 'string'],
            'experience_details' => ['nullable', 'string'],
            'reach_time' => ['nullable', 'string', 'max:255'],
            'awards' => ['nullable', 'string', 'max:255'],
            'mls_id' => ['nullable', 'string', 'max:255'],
            'languages' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);
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
