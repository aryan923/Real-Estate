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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
