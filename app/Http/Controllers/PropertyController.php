<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PropertyController extends Controller
{
    protected array $frontendPropertyRelations = ['images', 'categories', 'categories.parent', 'features', 'city', 'state', 'country'];

    public function index(Request $request): View
    {
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
            'features' => is_array($request->query('features')) ? array_map('intval', $request->query('features')) : [],
        ];

        $hasUserFilters = collect($filters)
            ->except(['sort', 'status'])
            ->contains(function ($value) {
                return $value !== null && $value !== '' && $value !== 0;
            });

        $propertiesQuery = Property::query()
            ->with($this->frontendPropertyRelations);

        $propertiesQuery->where('status', 'active');

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

        if (!empty($filters['features'])) {
            foreach ($filters['features'] as $featId) {
                $propertiesQuery->whereHas('features', function ($query) use ($featId) {
                    $query->where('features.id', $featId);
                });
            }
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
                ->with($this->frontendPropertyRelations)
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
            'features' => \App\Models\Feature::orderBy('name')->get(),
            'filterOptions' => [
                'types' => $typeOptions,
                'categories' => $categoryOptions,
                'states' => $stateOptions,
                'cities' => $cityOptions,
                'areas' => $areaOptions,
            ],
        ]);
    }

    public function show(string $slug): View
    {
        $property = Property::query()
            ->with($this->frontendPropertyRelations)
            ->where('slug', $slug)
            ->where('status', 'active')
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
            ->with($this->frontendPropertyRelations)
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
    }
}
