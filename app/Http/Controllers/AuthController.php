<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if ($user && !$user->is_admin) {
            return back()->withErrors([
                'email' => 'You are not authorized to access the admin panel.',
            ])->onlyInput('email');
        }

        if (Auth::attempt(array_merge($credentials, ['is_admin' => true]), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function sendResetOtpEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->input('email'))->where('is_admin', true)->first();

        if ($user) {
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = now()->addMinutes(10);

            \DB::table('password_reset_otps')->updateOrInsert(
                ['email' => $user->email],
                [
                    'otp' => $otp,
                    'expires_at' => $expiresAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            Mail::raw(
                "Your admin password reset OTP is: {$otp}. It expires in 10 minutes.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Admin Password Reset OTP');
                }
            );
        }

        return redirect()->route('admin.password.reset', ['email' => $request->input('email')])
            ->with('status', 'OTP sent successfully. Check your email for the code.');
    }

    public function resetWithOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $otpRecord = \DB::table('password_reset_otps')
            ->where('email', $request->input('email'))
            ->where('otp', $request->input('otp'))
            ->where('expires_at', '>', now())
            ->first();

        if (! $otpRecord) {
            return back()->withErrors(['otp' => 'The provided OTP is invalid or expired.'])->withInput();
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Unable to find a matching user account.'])->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->setRememberToken(Str::random(60));

        $user->save();

        \DB::table('password_reset_otps')->where('email', $user->email)->delete();

        return redirect()->route('admin.login')->with('status', 'Your password has been reset. Please sign in with your new password.');
    }
}
