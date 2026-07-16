<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->has('agentMeta')
            ->with('agentMeta');

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // Filter by Service Area (specialties tag)
        if ($request->filled('service_area')) {
            $serviceArea = $request->input('service_area');
            $query->whereHas('agentMeta', function ($q) use ($serviceArea) {
                $q->where('specialties', 'like', '%' . $serviceArea . '%');
            });
        }

        // Filter by Category
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            $category = Category::find($categoryId);
            if ($category) {
                $query->whereHas('agentMeta', function ($q) use ($category) {
                    $words = preg_split('/[\s\/,]+/', $category->name, -1, PREG_SPLIT_NO_EMPTY);
                    $q->where(function($sub) use ($words, $category) {
                        $sub->where('specialties', 'like', '%' . $category->name . '%')
                            ->orWhere('title', 'like', '%' . $category->name . '%');
                        foreach ($words as $word) {
                            if (strlen($word) > 3) {
                                $sub->orWhere('specialties', 'like', '%' . $word . '%')
                                    ->orWhere('title', 'like', '%' . $word . '%');
                            }
                        }
                    });
                });
            }
        }

        $agents = $query->orderBy('name')->paginate(6)->withQueryString();

        $allAgents = User::query()
            ->has('agentMeta')
            ->with('agentMeta')
            ->get();

        // Extract unique Service Areas from agent specialties
        $serviceAreas = collect();
        foreach ($allAgents as $agent) {
            $tagsStr = $agent->agentMeta?->specialties ?? '';
            $tags = array_map('trim', explode(',', $tagsStr));
            foreach ($tags as $tag) {
                if ($tag !== '') {
                    $serviceAreas->push($tag);
                }
            }
        }

        $serviceAreas = $serviceAreas->unique()
            ->sort()
            ->values()
            ->map(function ($areaName) use ($allAgents) {
                $count = $allAgents->filter(function ($agent) use ($areaName) {
                    $tagsStr = $agent->agentMeta?->specialties ?? '';
                    $tags = array_map('trim', explode(',', $tagsStr));
                    return in_array($areaName, $tags, true);
                })->count();

                return (object)[
                    'name' => $areaName,
                    'agents_count' => $count
                ];
            });

        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get()
            ->map(function ($category) use ($allAgents) {
                $words = preg_split('/[\s\/,]+/', $category->name, -1, PREG_SPLIT_NO_EMPTY);
                $agentCount = $allAgents->filter(function ($agent) use ($words, $category) {
                    $title = strtolower($agent->agentMeta?->title ?? '');
                    $specialties = strtolower($agent->agentMeta?->specialties ?? '');
                    $catName = strtolower($category->name);
                    if (str_contains($title, $catName) || str_contains($specialties, $catName)) {
                        return true;
                    }
                    foreach ($words as $word) {
                        if (strlen($word) > 3 && (str_contains($title, strtolower($word)) || str_contains($specialties, strtolower($word)))) {
                            return true;
                        }
                    }
                    return false;
                })->count();

                $category->agents_count = $agentCount;
                return $category;
            })
            ->filter(function($category) {
                return $category->agents_count > 0;
            })
            ->values();

        return view('agents', compact('agents', 'serviceAreas', 'categories'));
    }

    public function show(string $slug): View
    {
        $agentUser = User::query()
            ->has('agentMeta')
            ->with('agentMeta')
            ->get()
            ->first(function ($user) use ($slug) {
                return Str::slug($user->name) === $slug;
            });

        abort_unless($agentUser, 404);

        $agent = [
            'name' => $agentUser->name,
            'email' => $agentUser->email,
            'phone' => $agentUser->phone ?? 'N/A',
            'slug' => Str::slug($agentUser->name),
            'role' => $agentUser->agentMeta?->title ?? 'Agent',
            'company' => $agentUser->agentMeta?->company ?? 'Lux Estate',
            'reviews' => ($agentUser->id % 3) + 1,
            'member_of' => $agentUser->agentMeta?->member_of ?? 'National Association of Luxury Real Estate Professionals',
            'address' => $agentUser->agentMeta?->address ?? 'N/A',
            'image' => \App\Classes\ImageuploadHelper::getUploadedFileUrl($agentUser->profile_pic),
            'about_me' => $agentUser->agentMeta?->about_me ?? '',
            'specialties' => $agentUser->agentMeta?->specialties ? array_map('trim', explode(',', $agentUser->agentMeta->specialties)) : [],
            'experience_details' => $agentUser->agentMeta?->experience_details ?? 'N/A',
            'reach_time' => $agentUser->agentMeta?->reach_time ?? 'N/A',
            'awards' => $agentUser->agentMeta?->awards ?? 'N/A',
            'mls_id' => $agentUser->agentMeta?->mls_id ?? 'N/A',
            'languages' => $agentUser->agentMeta?->languages ?? 'N/A',
            'experience_years' => $agentUser->agentMeta?->experience_years ?? 'N/A',
            'facebook' => $agentUser->agentMeta?->facebook ?? '',
            'twitter' => $agentUser->agentMeta?->twitter ?? '',
            'linkedin' => $agentUser->agentMeta?->linkedin ?? '',
            'instagram' => $agentUser->agentMeta?->instagram ?? '',
            'youtube' => $agentUser->agentMeta?->youtube ?? '',
            'vimeo' => $agentUser->agentMeta?->vimeo ?? '',
            'whatsapp' => $agentUser->agentMeta?->whatsapp ?? '',
        ];

        $properties = Property::where('builder_developer', $agentUser->name)
            ->where('status', 'active')
            ->with(['city', 'categories'])
            ->get();

        return view('agent-detail', ['agent' => $agent, 'properties' => $properties]);
    }
}
