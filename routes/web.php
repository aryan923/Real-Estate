<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CityController as AdminCityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController as FrontendPropertyController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('enquiries', [EnquiryController::class, 'store'])->name('save_enq');

Route::get('/properties', [FrontendPropertyController::class, 'index'])->name('properties');

Route::get('/about-us', [HomeController::class, 'about'])->name('about-us');

Route::get('/contact-us', [HomeController::class, 'contact'])->name('contact-us');

Route::get('/agents', [AgentController::class, 'index'])->name('agents');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');

Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog-detail');

Route::post('/blog/{slug}/comment', [\App\Http\Controllers\CommentController::class, 'store'])->name('blog.comment.store');

Route::get('/agents/{slug}', [AgentController::class, 'show'])->name('agent-detail');

Route::get('/property-detail/{slug}', [FrontendPropertyController::class, 'show'])->name('property-detail');

Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');

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

         Route::get('/cities/data', [AdminCityController::class, 'data'])->name('cities.data');
         Route::resource('/cities', AdminCityController::class)
             ->only(['index', 'edit', 'update'])
             ->names([
                 'index' => 'cities',
                 'edit' => 'cities.edit',
                 'update' => 'cities.update',
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
