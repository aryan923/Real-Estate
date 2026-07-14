<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProperties = Property::with(['images', 'categories'])
            ->where('status', 'active')
            ->where('featured', true)
            ->inRandomOrder()
            ->take(2)
            ->get();

        if ($featuredProperties->count() < 2) {
            $featuredProperties = Property::with(['images', 'categories'])
                ->where('status', 'active')
                ->inRandomOrder()
                ->take(2)
                ->get();
        }

        $rentProperties = Property::with(['images', 'categories'])
            ->where('status', 'active')
            ->where('property_purpose', 'rent')
            ->latest()
            ->take(12)
            ->get();

        if ($rentProperties->count() < 12) {
            $rentProperties = Property::with(['images', 'categories'])
                ->where('status', 'active')
                ->latest()
                ->take(12)
                ->get();
        }

        $categories = Category::query()
            ->where('status', true)
            ->where('is_featured', true)
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'active');
            }])
            ->take(6)
            ->get();

        $cities = City::query()
            ->where('is_featured', true)
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderByDesc('properties_count')
            ->take(12)
            ->get();

        $latestProperties = Property::with(['images', 'categories', 'city'])
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $testimonials = Testimonial::query()
            ->where('status', true)
            ->where('is_featured', true)
            ->inRandomOrder()
            ->take(6)
            ->get();

        $adProperty = Property::with(['images', 'city', 'state', 'country'])
            ->where('status', 'active')
            ->where('is_advertisement', true)
            ->inRandomOrder()
            ->first();

        if (!$adProperty) {
            $adProperty = Property::with(['images', 'city', 'state', 'country'])
                ->where('status', 'active')
                ->inRandomOrder()
                ->first();
        }

        $featuredAgents = User::query()
            ->has('agentMeta')
            ->with('agentMeta')
            ->whereHas('agentMeta', function ($q) {
                $q->where('is_featured', true);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('index', compact('featuredProperties', 'rentProperties', 'categories', 'cities', 'latestProperties', 'testimonials', 'adProperty', 'featuredAgents'));
    }

    public function about(): View
    {
        return view('about-us');
    }

    public function contact(): View
    {
        return view('contact-us');
    }
}
