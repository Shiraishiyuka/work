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
        // ユーザー（従業員）用のログインページ
    Fortify::loginView(function () {
        return view('auth.login'); // 従業員ログインページ
    });

    // **管理者用のログインページ**
    Fortify::loginView(function () {
        return view('admin.auth.login'); // 管理者ログインページ
    });

    // 新規登録（従業員用のみ）
    Fortify::registerView(function () {
        return view('auth.register');
    });

    // **ログイン処理後のリダイレクト**
    Fortify::authenticateUsing(function (Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 管理者なら `/admin/dashboard` にリダイレクト
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            // 従業員なら `/dashboard` にリダイレクト
            return redirect()->route('attendance.show');
        }

        return null; // 認証失敗
    });

    // ログインのレート制限（1分間に10回まで）
    RateLimiter::for('login', function (Request $request) {
        $email = (string) $request->email;
        return Limit::perMinute(10)->by($email . $request->ip());
    });
    }
}



/*
public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
    });*/
