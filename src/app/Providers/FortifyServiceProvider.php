<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 修正: /login にアクセスしたとき、自作Bladeを返す
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 修正: Fortify の /register にアクセスしたときの画面
        // 今回は STEP1 を表示
        Fortify::registerView(function () {
            return view('auth.register-step1');
        });

        // 修正: 開発中はログイン試行回数を少し緩める
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
