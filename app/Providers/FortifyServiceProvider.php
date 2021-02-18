<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);


        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        // //if you want to add your own logic aunthenticate a user, you can do right here in this method
        // Fortify::authenticateUsing(function (Request  $request) {
        //     $user = User::where('name', $request->email)->first();

        //     if ($user && Hash::check($request->password, $user->password)) {
        //         return $user;
        //     }
        // });

        // Fortify::requestPasswordResetLinkView(function () {
        //     return view('auth.passwords.forgot-password');
        // });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.passwords.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function ($request) {
            return view('auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.passwords.password-confirm');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });
    }
}
