<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$tailAdminPages = [
    '404',
    'alerts',
    'avatars',
    'badge',
    'bar-chart',
    'basic-tables',
    'blank',
    'buttons',
    'calendar',
    'form-elements',
    'images',
    'index',
    'line-chart',
    'profile',
    'sidebar',
    'signin',
    'videos',
    'properties',
];



$frontendPropertyRelations = ['images', 'categories', 'categories.parent', 'features', 'city', 'state', 'country'];

Route::get('/', function () {
    $featuredProperties = \App\Models\Property::with(['images', 'categories'])
        ->where('status', 'active')
        ->where('featured', true)
        ->latest()
        ->take(2)
        ->get();

    if ($featuredProperties->count() < 2) {
        $featuredProperties = \App\Models\Property::with(['images', 'categories'])
            ->where('status', 'active')
            ->latest()
            ->take(2)
            ->get();
    }

    $rentProperties = \App\Models\Property::with(['images', 'categories'])
        ->where('status', 'active')
        ->where('property_purpose', 'rent')
        ->latest()
        ->take(3)
        ->get();

    if ($rentProperties->count() < 3) {
        $rentProperties = \App\Models\Property::with(['images', 'categories'])
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get();
    }

    $categories = \App\Models\Category::query()
        ->where('status', true)
        ->withCount(['properties' => function ($query) {
            $query->where('status', 'active');
        }])
        ->take(6)
        ->get();

    $cities = \App\Models\City::query()
        ->withCount(['properties' => function ($query) {
            $query->where('status', 'active');
        }])
        ->orderByDesc('properties_count')
        ->take(12)
        ->get();

    $latestProperties = \App\Models\Property::with(['images', 'categories', 'city'])
        ->where('status', 'active')
        ->latest()
        ->take(12)
        ->get();

    $testimonials = \App\Models\Testimonial::query()
        ->where('status', true)
        ->latest()
        ->take(6)
        ->get();

    return view('index', compact('featuredProperties', 'rentProperties', 'categories', 'cities', 'latestProperties', 'testimonials'));
})->name('home');

Route::post('enquiries', [EnquiryController::class, 'store'])->name('save_enq');

Route::get('/properties', function (Request $request) use ($frontendPropertyRelations) {
    $filters = [
        'q' => trim((string) $request->query('q', '')),
        'type' => strtolower(trim((string) $request->query('type', ''))),
        'category' => (int) $request->query('category', 0),
        'state' => (int) $request->query('state', 0),
        'city' => (int) $request->query('city', 0),
        'area' => trim((string) $request->query('area', '')),
        'min_price' => is_numeric($request->query('min_price')) ? (float) $request->query('min_price') : null,
        'max_price' => is_numeric($request->query('max_price')) ? (float) $request->query('max_price') : null,
        'beds' => is_numeric($request->query('beds')) ? (int) $request->query('beds') : null,
        'baths' => is_numeric($request->query('baths')) ? (int) $request->query('baths') : null,
        'status' => trim((string) $request->query('status', '')),
        'floors' => is_numeric($request->query('floors')) ? (int) $request->query('floors') : null,
        'sort' => trim((string) $request->query('sort', 'newest')),
    ];

    $hasUserFilters = collect($filters)
        ->except(['sort', 'status'])
        ->contains(function ($value) {
            return $value !== null && $value !== '' && $value !== 0;
        });

    $propertiesQuery = Property::query()
        ->with($frontendPropertyRelations);

    if ($filters['status'] !== '') {
        $propertiesQuery->where('status', $filters['status']);
    } else {
        $propertiesQuery->where('status', 'active');
    }

    if ($filters['q'] !== '') {
        $searchTerm = $filters['q'];
        $propertiesQuery->where(function ($query) use ($searchTerm) {
            $query->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%")
                ->orWhere('locality', 'like', "%{$searchTerm}%")
                ->orWhereHas('city', function ($cityQuery) use ($searchTerm) {
                    $cityQuery->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('state', function ($stateQuery) use ($searchTerm) {
                    $stateQuery->where('name', 'like', "%{$searchTerm}%");
                });
        });
    }

    if ($filters['type'] !== '') {
        $propertiesQuery->whereRaw('LOWER(property_purpose) = ?', [$filters['type']]);
    }

    if ($filters['category'] > 0) {
        $propertiesQuery->whereHas('categories', function ($query) use ($filters) {
            $query->where('categories.id', $filters['category']);
        });
    }

    if ($filters['state'] > 0) {
        $propertiesQuery->where('state_id', $filters['state']);
    }

    if ($filters['city'] > 0) {
        $propertiesQuery->where('city_id', $filters['city']);
    }

    if ($filters['area'] !== '') {
        $propertiesQuery->where('locality', $filters['area']);
    }

    if ($filters['min_price'] !== null) {
        $propertiesQuery->whereRaw('CAST(REPLACE(price, ",", "") AS DECIMAL(15,2)) >= ?', [$filters['min_price']]);
    }

    if ($filters['max_price'] !== null) {
        $propertiesQuery->whereRaw('CAST(REPLACE(price, ",", "") AS DECIMAL(15,2)) <= ?', [$filters['max_price']]);
    }

    if ($filters['beds'] !== null) {
        $propertiesQuery->where('bedrooms', '>=', $filters['beds']);
    }

    if ($filters['baths'] !== null) {
        $propertiesQuery->where('bathrooms', '>=', $filters['baths']);
    }

    if ($filters['floors'] !== null) {
        $propertiesQuery->where('floors', '>=', $filters['floors']);
    }

    switch ($filters['sort']) {
        case 'price_asc':
            $propertiesQuery->orderByRaw('CAST(REPLACE(price, ",", "") AS DECIMAL(15,2)) asc');
            break;
        case 'price_desc':
            $propertiesQuery->orderByRaw('CAST(REPLACE(price, ",", "") AS DECIMAL(15,2)) desc');
            break;
        case 'featured':
            $propertiesQuery->orderByDesc('featured')->latest('id');
            break;
        default:
            $propertiesQuery->latest('id');
            break;
    }

    $properties = $propertiesQuery
        ->paginate(6)
        ->withQueryString();

    if (!$hasUserFilters && $properties->isEmpty()) {
        $properties = Property::query()
            ->with($frontendPropertyRelations)
            ->latest('id')
            ->paginate(6)
            ->withQueryString();
    }

    $typeOptions = Property::query()
        ->where('status', 'active')
        ->whereNotNull('property_purpose')
        ->where('property_purpose', '!=', '')
        ->selectRaw('LOWER(property_purpose) as value, MIN(property_purpose) as label, COUNT(*) as aggregate')
        ->groupBy('value')
        ->orderBy('label')
        ->get();

    $categoryOptions = Category::query()
        ->where('status', true)
        ->withCount([
            'properties as active_properties_count' => function ($query) {
                $query->where('status', 'active');
            },
        ])
        ->get()
        ->filter(function ($category) {
            return $category->active_properties_count > 0;
        })
        ->sortBy('name')
        ->values();

    $stateOptions = DB::table('properties')
        ->join('states', 'states.id', '=', 'properties.state_id')
        ->where('properties.status', 'active')
        ->whereNull('properties.deleted_at')
        ->select('states.id', 'states.name', DB::raw('COUNT(*) as aggregate'))
        ->groupBy('states.id', 'states.name')
        ->orderBy('states.name')
        ->get();

    $cityOptions = DB::table('properties')
        ->join('cities', 'cities.id', '=', 'properties.city_id')
        ->where('properties.status', 'active')
        ->whereNull('properties.deleted_at')
        ->select('cities.id', 'cities.name', DB::raw('COUNT(*) as aggregate'))
        ->groupBy('cities.id', 'cities.name')
        ->orderBy('cities.name')
        ->get();

    $areaOptions = DB::table('properties')
        ->where('status', 'active')
        ->whereNull('deleted_at')
        ->whereNotNull('locality')
        ->where('locality', '!=', '')
        ->select('locality as name', DB::raw('COUNT(*) as aggregate'))
        ->groupBy('locality')
        ->orderBy('locality')
        ->get();

    return view('properties', [
        'properties' => $properties,
        'filters' => $filters,
        'filterOptions' => [
            'types' => $typeOptions,
            'categories' => $categoryOptions,
            'states' => $stateOptions,
            'cities' => $cityOptions,
            'areas' => $areaOptions,
        ],
    ]);
})->name('properties');

Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact-us');

Route::get('/agents', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\User::query()
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
        $category = \App\Models\Category::find($categoryId);
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

    $allAgents = \App\Models\User::query()
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

    $categories = \App\Models\Category::where('status', true)
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
})->name('agents');

Route::get('/blog', function () {
    $allPosts = \App\Models\Post::where('status', true)->latest()->get();

    $query = trim((string) request('q', ''));
    if ($query !== '') {
        $posts = \App\Models\Post::where('status', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(4)
            ->withQueryString();
    } else {
        $posts = \App\Models\Post::where('status', true)->latest()->paginate(4);
    }

    $recentPosts = $allPosts->take(4);
    $featuredPosts = $allPosts->slice(1, 2);

    return view('blog', compact('allPosts', 'posts', 'recentPosts', 'featuredPosts'));
})->name('blog');

Route::get('/blog/{slug}', function (string $slug) {
    $post = \App\Models\Post::where('slug', $slug)->firstOrFail();
    $allPosts = \App\Models\Post::where('status', true)->latest()->get();
    $recentPosts = $allPosts->take(4);

    return view('blog-detail', [
        'post' => $post,
        'recentPosts' => $recentPosts,
    ]);
})->name('blog-detail');

Route::post('/blog/{slug}/comment', [\App\Http\Controllers\CommentController::class, 'store'])->name('blog.comment.store');

Route::get('/agents/{slug}', function (string $slug) {
    $agentUser = \App\Models\User::query()
        ->has('agentMeta')
        ->with('agentMeta')
        ->get()
        ->first(function ($user) use ($slug) {
            return \Illuminate\Support\Str::slug($user->name) === $slug;
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
        'slug' => \Illuminate\Support\Str::slug($agentUser->name),
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

    $properties = \App\Models\Property::where('builder_developer', $agentUser->name)
        ->where('status', 'active')
        ->with(['city', 'categories'])
        ->get();

    return view('agent-detail', ['agent' => $agent, 'properties' => $properties]);
})->name('agent-detail');

Route::get('/property-detail/{slug}', function (string $slug) use ($frontendPropertyRelations) {
    $property = Property::query()
        ->with($frontendPropertyRelations)
        ->where('slug', $slug)
        ->firstOrFail();

    // Gather matching criteria
    $categoryIds  = $property->categories->pluck('id')->toArray();
    $cityId       = $property->city_id;
    $purpose      = $property->property_purpose;
    $price        = (float) $property->price;
    $minPrice     = $price > 0 ? $price * 0.5 : 0;
    $maxPrice     = $price > 0 ? $price * 1.5 : PHP_INT_MAX;

    // Fetch candidate pool: active, not current, limit to 200
    $candidates = Property::query()
        ->with($frontendPropertyRelations)
        ->where('status', 'active')
        ->where('id', '!=', $property->id)
        ->limit(200)
        ->get();

    // Score each candidate
    $scored = $candidates->map(function ($p) use ($categoryIds, $cityId, $purpose, $minPrice, $maxPrice) {
        $score = 0;
        // Same category overlap: 3 pts per shared category
        $sharedCategories = $p->categories->pluck('id')->intersect($categoryIds)->count();
        $score += $sharedCategories * 3;
        // Same city: 2 pts
        if ($p->city_id && $p->city_id === $cityId) $score += 2;
        // Same purpose (buy/rent): 2 pts
        if (strtolower($p->property_purpose) === strtolower($purpose)) $score += 2;
        // Similar price range ±50%: 1 pt
        $pPrice = (float) $p->price;
        if ($pPrice >= $minPrice && $pPrice <= $maxPrice) $score += 1;
        $p->similarity_score = $score;
        return $p;
    })
    ->filter(fn($p) => $p->similarity_score > 0)
    ->sortByDesc('similarity_score')
    ->take(8)
    ->values();

    return view('property-detail', [
        'property'           => $property,
        'similarProperties'  => $scored,
    ]);
})->name('property-detail');

Route::redirect('/admin', '/admin/dashboard')->name('admin');

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::prefix('admin')->name('admin.')->group(function () use ($tailAdminPages) {
     Route::get('/login', function () {
         if (Auth::check()) {
             return redirect()->route('admin.dashboard');
         }

         return view('admin.pages.signin');
     })->name('login');

     Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
     Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
 
     Route::get('/forgot-password', function () {
         if (Auth::check()) {
             return redirect()->route('admin.dashboard');
         }

         return view('admin.pages.forgot-password');
     })->name('forgot-password');
     Route::post('/forgot-password', [\App\Http\Controllers\AuthController::class, 'sendResetOtpEmail'])->name('password.email');
     Route::get('/reset-password', function () {
         if (Auth::check()) {
             return redirect()->route('admin.dashboard');
         }

         return view('admin.pages.reset-password', [
             'email' => request()->query('email'),
         ]);
     })->name('password.reset');
     Route::post('/reset-password', [\App\Http\Controllers\AuthController::class, 'resetWithOtp'])->name('password.update');
 
     Route::get('/signup', fn () => redirect()->route('admin.login'))->name('signup');
 
     Route::middleware('auth')->group(function () use ($tailAdminPages) {
         Route::view('/dashboard', 'admin.pages.dashboard')->name('dashboard');
         Route::view('/404', 'admin.pages.404')->name('not-found');

         Route::get('/general-setting', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('general-setting');
         Route::post('/general-setting', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('general-setting.update');
         Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
         Route::resource('/categories', CategoryController::class)
             ->except(['show'])
             ->names([
                 'index' => 'categories',
                 'create' => 'categories.create',
                 'store' => 'categories.store',
                 'edit' => 'categories.edit',
                 'update' => 'categories.update',
                 'destroy' => 'categories.destroy',
             ]);
         Route::get('/features/data', [FeatureController::class, 'data'])->name('features.data');
         Route::resource('/features', FeatureController::class)
             ->except(['show'])
             ->names([
                 'index' => 'features',
                 'create' => 'features.create',
                 'store' => 'features.store',
                 'edit' => 'features.edit',
                 'update' => 'features.update',
                 'destroy' => 'features.destroy',
             ]);
         Route::get('/properties/data', [PropertyController::class, 'data'])->name('properties.data');
         Route::get('/states/by-country/{country}', [PropertyController::class, 'statesByCountry'])->name('states.by-country');
         Route::get('/cities/by-state/{state}', [PropertyController::class, 'citiesByState'])->name('cities.by-state');
         Route::resource('/properties', PropertyController::class)
             ->except(['show'])
             ->names([
                 'index' => 'properties',
                 'create' => 'properties.create',
                 'store' => 'properties.store',
                 'edit' => 'properties.edit',
                 'update' => 'properties.update',
                 'destroy' => 'properties.destroy',
             ]);

         Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
         Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
         Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
         Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');
         Route::get('/user-management/data', [UserManagementController::class, 'data'])->name('user-management.data');
         Route::get('/user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
         Route::post('/user-management', [UserManagementController::class, 'store'])->name('user-management.store');
         Route::get('/user-management/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
         Route::put('/user-management/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
         Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
         Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions');
         Route::get('/roles-permissions/create', [RolePermissionController::class, 'create'])->name('roles-permissions.create');
         Route::post('/roles-permissions', [RolePermissionController::class, 'store'])->name('roles-permissions.store');
         Route::get('/roles-permissions/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles-permissions.edit');
         Route::put('/roles-permissions/{role}', [RolePermissionController::class, 'update'])->name('roles-permissions.update');
         Route::delete('/roles-permissions/{role}', [RolePermissionController::class, 'destroy'])->name('roles-permissions.destroy');
 
         Route::prefix('enquiries')->name('enquiries.')->group(function () {
             Route::get('/', [EnquiryController::class, 'index'])->name('index');
             Route::get('/data', [EnquiryController::class, 'data'])->name('data');
             Route::get('/{enquiry}', [EnquiryController::class, 'show'])->name('show');
             Route::get('/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('edit');
             Route::put('/{enquiry}', [EnquiryController::class, 'update'])->name('update');
             Route::delete('/{enquiry}', [EnquiryController::class, 'destroy'])->name('destroy');
         });
         Route::get('/testimonials/data', [\App\Http\Controllers\Admin\TestimonialController::class, 'data'])->name('testimonials.data');
         Route::resource('/testimonials', \App\Http\Controllers\Admin\TestimonialController::class)
             ->except(['show'])
             ->names([
                 'index' => 'testimonials',
                 'create' => 'testimonials.create',
                 'store' => 'testimonials.store',
                 'edit' => 'testimonials.edit',
                 'update' => 'testimonials.update',
                 'destroy' => 'testimonials.destroy',
             ]);
          Route::get('/agents/data', [\App\Http\Controllers\Admin\AgentController::class, 'data'])->name('agents.data');
          Route::resource('/agents', \App\Http\Controllers\Admin\AgentController::class)
              ->except(['show'])
              ->names([
                  'index' => 'agents',
                  'create' => 'agents.create',
                  'store' => 'agents.store',
                  'edit' => 'agents.edit',
                  'update' => 'agents.update',
                  'destroy' => 'agents.destroy',
              ]);
          Route::get('/posts/data', [\App\Http\Controllers\Admin\PostController::class, 'data'])->name('posts.data');
          Route::resource('/posts', \App\Http\Controllers\Admin\PostController::class)
              ->except(['show'])
              ->names([
                  'index' => 'posts',
                  'create' => 'posts.create',
                  'store' => 'posts.store',
                  'edit' => 'posts.edit',
                  'update' => 'posts.update',
                  'destroy' => 'posts.destroy',
              ]);

          Route::get('/comments/data', [\App\Http\Controllers\Admin\CommentController::class, 'data'])->name('comments.data');
          Route::post('/comments/{comment}/toggle', [\App\Http\Controllers\Admin\CommentController::class, 'toggle'])->name('comments.toggle');
          Route::resource('/comments', \App\Http\Controllers\Admin\CommentController::class)
              ->only(['index', 'destroy'])
              ->names([
                  'index' => 'comments',
                  'destroy' => 'comments.destroy',
              ]);
         Route::get('/{page}', function (string $page) use ($tailAdminPages) {
             if (in_array($page, $tailAdminPages, true) && view()->exists("admin.pages.{$page}")) {
                 return view("admin.pages.{$page}");
             }
 
             return response()->view('admin.pages.404', [], 404);
         })->where('page', '[A-Za-z0-9\-]+')->name('page');
     });
 });
