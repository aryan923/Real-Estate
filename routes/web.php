<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/properties', function () {
    return view('properties');
})->name('properties');

Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact-us');

Route::get('/agents', function () {
    return view('agents');
})->name('agents');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');

Route::get('/agents/{slug}', function (string $slug) {
    $agents = [
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

    $agent = collect($agents)->firstWhere('slug', Str::slug($slug));
    abort_unless($agent, 404);

    return view('agent-detail', ['agent' => $agent]);
})->name('agent-detail');

Route::get('/property-detail', function () {
    return view('property-detail');
})->name('property-detail');
