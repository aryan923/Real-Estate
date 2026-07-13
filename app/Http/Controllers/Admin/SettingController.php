<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ImageuploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.pages.general-setting', [
            'settings' => Setting::allAsArray(),
        ]);
    }

    public function update(): RedirectResponse
    {
        $validated = request()->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_logo' => ['nullable', 'image', 'max:2048'],
            'site_fav' => ['nullable', 'image', 'max:1024'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_x' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'footer_address' => ['nullable', 'string', 'max:500'],
            'footer_phone_1' => ['nullable', 'string', 'max:50'],
            'footer_phone_2' => ['nullable', 'string', 'max:50'],
            'footer_email' => ['nullable', 'email', 'max:255'],
            'footer_about' => ['nullable', 'string', 'max:1000'],
            'footer_copyright' => ['nullable', 'string', 'max:255'],
            'footer_links' => ['nullable', 'string', 'max:500'],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_encryption' => ['nullable', 'string', 'max:20'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_from_address' => ['nullable', 'email', 'max:255'],
        ]);

        if (request()->hasFile('site_logo')) {
            $validated['site_logo'] = ImageuploadHelper::upload(request()->file('site_logo'), 'settings/logo');
        }

        if (request()->hasFile('site_fav')) {
            $validated['site_fav'] = ImageuploadHelper::upload(request()->file('site_fav'), 'settings/favicon');
        }

        foreach ($validated as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('status', 'Settings updated successfully.');
    }
}
