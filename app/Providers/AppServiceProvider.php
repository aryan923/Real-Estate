<?php

namespace App\Providers;

use App\Classes\ImageuploadHelper;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/price_helper.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });

        // Register CRM Lifecycle Automation Observers
        \App\Models\Booking::observe(\App\Observers\BookingObserver::class);
        \App\Models\SiteVisit::observe(\App\Observers\SiteVisitObserver::class);
        \App\Models\FollowUp::observe(\App\Observers\FollowUpObserver::class);

        View::composer('*', function ($view) {
            $settings = Setting::allAsArray();

            $view->with('settings', $settings);
            $view->with('adminSiteName', $settings['site_name'] ?? config('app.name', 'TailAdmin'));
            $view->with('adminFavicon', !empty($settings['site_fav'])
                ? ImageuploadHelper::getUploadedFileUrl($settings['site_fav'])
                : asset('vendor/tailadmin/favicon.ico'));
            $view->with('adminSiteLogo', !empty($settings['site_logo'])
                ? ImageuploadHelper::getUploadedFileUrl($settings['site_logo'])
                : asset('vendor/tailadmin/src/images/logo/logo.svg'));
        });
    }
}
