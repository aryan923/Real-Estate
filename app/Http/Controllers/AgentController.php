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

        if (!$agentUser) {
            $staticAgents = [
                [
                    'name' => 'Amanda Smithson',
                    'slug' => 'amanda-smithson',
                    'role' => 'Senior Manager',
                    'company' => 'Lux Estate',
                    'reviews' => 3,
                    'member_of' => 'National Association of Luxury Real Estate Professionals',
                    'address' => '120 Greenwich St, Suite 220, Manhattan, New York, NY 10006',
                    'phone' => '1-800-555-1021',
                    'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'name' => 'Samuel Diesel',
                    'slug' => 'samuel-diesel',
                    'role' => 'Commercial Broker',
                    'company' => 'Lux Estate',
                    'reviews' => 1,
                    'member_of' => 'National Association of Luxury Real Estate Professionals',
                    'address' => '1234 Beacon Street, Suite 101, Floor 3 The Harrison Tower Back Bay, Boston, MA 02118',
                    'phone' => '1-800-555-1234',
                    'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'name' => 'Lily Bicharm',
                    'slug' => 'lily-bicharm',
                    'role' => 'Residential Agent',
                    'company' => 'Lux Estate',
                    'reviews' => 2,
                    'member_of' => 'National Association of Luxury Real Estate Professionals',
                    'address' => '370 Bay Street, Level 9, Toronto, ON M5H 2R2',
                    'phone' => '1-800-555-1933',
                    'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'name' => 'Dennis Albo',
                    'slug' => 'dennis-albo',
                    'role' => 'Investment Advisor',
                    'company' => 'Lux Estate',
                    'reviews' => 2,
                    'member_of' => 'National Association of Luxury Real Estate Professionals',
                    'address' => '99 King Street, Suite 12, Seattle, WA 98104',
                    'phone' => '1-800-555-4020',
                    'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'name' => 'Maria Fields',
                    'slug' => 'maria-fields',
                    'role' => 'Leasing Specialist',
                    'company' => 'Lux Estate',
                    'reviews' => 4,
                    'member_of' => 'National Association of Luxury Real Estate Professionals',
                    'address' => '55 Bayshore Ave, Unit 8, San Diego, CA 92101',
                    'phone' => '1-800-555-5531',
                    'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'name' => 'Michael Sutther',
                    'slug' => 'michael-sutther',
                    'role' => 'Realtor',
                    'company' => 'Lux Estate',
                    'reviews' => 1,
                    'member_of' => 'National Association of Luxury Real Estate Professionals',
                    'address' => '481 Harbor Drive, Suite 30, Miami, FL 33132',
                    'phone' => '1-800-555-8842',
                    'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=900&q=80',
                ],
            ];

            $agent = collect($staticAgents)->firstWhere('slug', $slug);
            abort_unless($agent, 404);
            return view('agent-detail', ['agent' => $agent, 'properties' => collect()]);
        }

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
