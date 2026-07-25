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
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\SiteVisitController;
use App\Http\Controllers\Admin\FollowUpController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\ReportController;
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
Route::get('/list-property', [HomeController::class, 'listProperty'])->name('list-property');
Route::post('/list-property', [HomeController::class, 'storeListProperty'])->name('list-property.store');
Route::get('/api/states/{country}', [HomeController::class, 'getStates'])->name('api.states');
Route::get('/api/cities/{state}', [HomeController::class, 'getCities'])->name('api.cities');

Route::get('/p/{slug}', [HomeController::class, 'showCmsPage'])->name('cms-page');

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
         Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard')->middleware('can:view_dashboard');
         Route::view('/404', 'admin.pages.404')->name('not-found');

         // Profile Routes
         Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
         Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
         Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

         // General Setting
         Route::middleware('can:view_settings')->group(function () {
             Route::get('/general-setting', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('general-setting');
             Route::post('/general-setting', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('general-setting.update');
         });

         // Categories
         Route::middleware('can:view_categories')->group(function () {
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
         });

         // Features
         Route::middleware('can:view_features')->group(function () {
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
         });

         // Properties
         Route::middleware('can:view_properties')->group(function () {
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
         });

         // Cities
         Route::middleware('can:view_cities')->group(function () {
             Route::get('/cities/data', [AdminCityController::class, 'data'])->name('cities.data');
             Route::resource('/cities', AdminCityController::class)
                 ->only(['index', 'edit', 'update'])
                 ->names([
                     'index' => 'cities',
                     'edit' => 'cities.edit',
                     'update' => 'cities.update',
                 ]);
         });

         // Currencies
         Route::middleware('can:view_currencies')->group(function () {
             Route::get('/currencies/data', [CurrencyController::class, 'data'])->name('currencies.data');
             Route::resource('/currencies', CurrencyController::class)
                 ->except(['show'])
                 ->names([
                     'index' => 'currencies',
                     'create' => 'currencies.create',
                     'store' => 'currencies.store',
                     'edit' => 'currencies.edit',
                     'update' => 'currencies.update',
                     'destroy' => 'currencies.destroy',
                 ]);
         });

         // User Management
         Route::middleware('can:view_users')->group(function () {
             Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');
             Route::get('/user-management/data', [UserManagementController::class, 'data'])->name('user-management.data');
             Route::get('/user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
             Route::post('/user-management', [UserManagementController::class, 'store'])->name('user-management.store');
             Route::get('/user-management/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
             Route::put('/user-management/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
             Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
         });

         // Roles & Permissions
         Route::middleware('can:view_roles')->group(function () {
             Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions');
             Route::get('/roles-permissions/create', [RolePermissionController::class, 'create'])->name('roles-permissions.create');
             Route::post('/roles-permissions', [RolePermissionController::class, 'store'])->name('roles-permissions.store');
             Route::get('/roles-permissions/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles-permissions.edit');
             Route::put('/roles-permissions/{role}', [RolePermissionController::class, 'update'])->name('roles-permissions.update');
             Route::delete('/roles-permissions/{role}', [RolePermissionController::class, 'destroy'])->name('roles-permissions.destroy');
         });

         // Leads
         Route::middleware('can:view_enquiries')->group(function () {
             Route::prefix('enquiries')->name('enquiries.')->group(function () {
                 Route::get('/', [EnquiryController::class, 'index'])->name('index');
                 Route::get('/data', [EnquiryController::class, 'data'])->name('data');
                 Route::post('/', [EnquiryController::class, 'store'])->name('store');
                 Route::post('/import', [EnquiryController::class, 'import'])->name('import');
                 Route::post('/bulk-action', [EnquiryController::class, 'bulkAction'])->name('bulk-action');
                 Route::get('/{enquiry}', [EnquiryController::class, 'show'])->name('show');
                 Route::get('/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('edit');
                 Route::put('/{enquiry}', [EnquiryController::class, 'update'])->name('update');
                 Route::post('/{enquiry}/convert-to-client', [EnquiryController::class, 'convertToClient'])->name('convert-to-client');
                 Route::delete('/{enquiry}', [EnquiryController::class, 'destroy'])->name('destroy');
             });
         });

         // Testimonials
         Route::middleware('can:view_testimonials')->group(function () {
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
         });

         // Agents
         Route::middleware('can:view_agents')->group(function () {
             Route::get('/agents/data', [\App\Http\Controllers\Admin\AgentController::class, 'data'])->name('agents.data');
             Route::resource('/agents', \App\Http\Controllers\Admin\AgentController::class)
                 ->names([
                     'index' => 'agents',
                     'create' => 'agents.create',
                     'store' => 'agents.store',
                     'show' => 'agents.show',
                     'edit' => 'agents.edit',
                     'update' => 'agents.update',
                     'destroy' => 'agents.destroy',
                 ]);
         });

         // Blog
         Route::middleware('can:view_posts')->group(function () {
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
         });

         // Comments
         Route::middleware('can:view_comments')->group(function () {
             Route::get('/comments/data', [\App\Http\Controllers\Admin\CommentController::class, 'data'])->name('comments.data');
             Route::post('/comments/{comment}/toggle', [\App\Http\Controllers\Admin\CommentController::class, 'toggle'])->name('comments.toggle');
             Route::resource('/comments', \App\Http\Controllers\Admin\CommentController::class)
                 ->only(['index', 'destroy'])
                 ->names([
                     'index' => 'comments',
                     'destroy' => 'comments.destroy',
                 ]);
         });

         // Pages
         Route::middleware('can:view_pages')->group(function () {
             Route::get('/pages/data', [\App\Http\Controllers\Admin\PageController::class, 'data'])->name('pages.data');
             Route::resource('/pages', \App\Http\Controllers\Admin\PageController::class)
                 ->except(['show'])
                 ->names([
                     'index' => 'pages',
                     'create' => 'pages.create',
                     'store' => 'pages.store',
                     'edit' => 'pages.edit',
                     'update' => 'pages.update',
                     'destroy' => 'pages.destroy',
                 ]);
         });
          // Projects
          Route::get('/projects/data', [ProjectController::class, 'data'])->name('projects.data');
          Route::resource('/projects', ProjectController::class)->names([
              'index' => 'projects.index',
              'create' => 'projects.create',
              'store' => 'projects.store',
              'show' => 'projects.show',
              'edit' => 'projects.edit',
              'update' => 'projects.update',
              'destroy' => 'projects.destroy',
          ]);

          // Customers
          Route::get('/customers/data', [CustomerController::class, 'data'])->name('customers.data');
          Route::resource('/customers', CustomerController::class)->names([
              'index' => 'customers.index',
              'create' => 'customers.create',
              'store' => 'customers.store',
              'show' => 'customers.show',
              'edit' => 'customers.edit',
              'update' => 'customers.update',
              'destroy' => 'customers.destroy',
          ]);

          // Inventory
          Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
          Route::get('/inventory/unit/{unit}', [InventoryController::class, 'showUnit'])->name('inventory.unit.show');
          Route::post('/inventory/reserve/{unit}', [InventoryController::class, 'reserveUnit'])->name('inventory.unit.reserve');

          // Follow-ups
          Route::get('/follow-ups', fn() => redirect()->route('admin.enquiries.index'))->name('follow-ups.index');
          Route::resource('/follow-ups', FollowUpController::class)->except(['index', 'show'])->names([
              'create' => 'follow-ups.create',
              'store' => 'follow-ups.store',
              'edit' => 'follow-ups.edit',
              'update' => 'follow-ups.update',
              'destroy' => 'follow-ups.destroy',
          ]);
          Route::post('/follow-ups/{followUp}/log', [FollowUpController::class, 'logOutcome'])->name('follow-ups.log-outcome');

          // Site Visits
          Route::get('/site-visits/data', [SiteVisitController::class, 'data'])->name('site-visits.data');
          Route::resource('/site-visits', SiteVisitController::class)->names([
              'index' => 'site-visits.index',
              'create' => 'site-visits.create',
              'store' => 'site-visits.store',
              'show' => 'site-visits.show',
              'edit' => 'site-visits.edit',
              'update' => 'site-visits.update',
              'destroy' => 'site-visits.destroy',
          ]);
          Route::post('/site-visits/{siteVisit}/log', [SiteVisitController::class, 'logFeedback'])->name('site-visits.log-feedback');

          // Bookings
          Route::get('/bookings/data', [BookingController::class, 'data'])->name('bookings.data');
          Route::resource('/bookings', BookingController::class)->names([
              'index' => 'bookings.index',
              'create' => 'bookings.create',
              'store' => 'bookings.store',
              'show' => 'bookings.show',
              'edit' => 'bookings.edit',
              'update' => 'bookings.update',
              'destroy' => 'bookings.destroy',
          ]);
          Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
          Route::post('/bookings/{booking}/generate-installments', [PaymentController::class, 'generateMilestones'])->name('bookings.generate-installments');

          // Payments
          Route::get('/payments/data', [PaymentController::class, 'data'])->name('payments.data');
          Route::resource('/payments', PaymentController::class)->names([
              'index' => 'payments.index',
              'create' => 'payments.create',
              'store' => 'payments.store',
              'show' => 'payments.show',
              'edit' => 'payments.edit',
              'update' => 'payments.update',
              'destroy' => 'payments.destroy',
          ]);
          Route::get('/payments/{payment}/receipt', [PaymentController::class, 'generateReceipt'])->name('payments.receipt');

          // Documents
          Route::resource('/documents', DocumentController::class)->only(['index', 'store', 'destroy'])->names([
              'index' => 'documents.index',
              'store' => 'documents.store',
              'destroy' => 'documents.destroy',
          ]);

          // Reports
          Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
          Route::get('/reports/{report}/export', [ReportController::class, 'export'])->name('reports.export');

          Route::get('/{page}', function (string $page) use ($tailAdminPages) {
             if (in_array($page, $tailAdminPages, true) && view()->exists("admin.pages.{$page}")) {
                 return view("admin.pages.{$page}");
             }
 
             return response()->view('admin.pages.404', [], 404);
         })->where('page', '[A-Za-z0-9\-]+')->name('page');
     });
 });
