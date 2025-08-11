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
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Support\Facades\URL; // 💡 URLファサードを追加

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ログイン成功時のリダイレクト先をカスタマイズ
        $this->app->singleton(
            LoginResponse::class,
            function ($app) {
                return new class implements LoginResponse
                {
                    public function toResponse($request)
                    {
                        // セッションから現在のロケールを取得。なければデフォルトロケールを使用。
                        $locale = Session::get('locale', config('app.locale'));
                        // 🔥 認証後のリダイレクト先を、ロケールを含むURLを直接生成して指定
                        return redirect()->to(URL::to($locale . '/dashboard'));
                    }
                };
            }
        );

        // 登録成功時のリダイレクト先をカスタマイズ
        $this->app->singleton(
            RegisterResponse::class,
            function ($app) {
                return new class implements RegisterResponse
                {
                    public function toResponse($request)
                    {
                        // セッションから現在のロケールを取得。なければデフォルトロケールを使用。
                        $locale = Session::get('locale', config('app.locale'));
                        // 🔥 認証後のリダイレクト先を、ロケールを含むURLを直接生成して指定
                        return redirect()->to(URL::to($locale . '/dashboard'));
                    }
                };
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
