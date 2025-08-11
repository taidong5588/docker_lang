<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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

        // アプリケーション起動時にセッションからロケールを設定（SetLocaleミドルウェアが設定した値）
        App::setLocale(Session::get('locale', config('languages.fallback', 'en')));

        // Inertia にロケール情報を共有
        Inertia::share([
            'locale' => fn () => app()->getLocale(),
            'fallback_locale' => fn () => config('languages.fallback', 'en'),
            'supported_locales' => fn () => config('languages.supported'),
        ]);

        // Jetstreamの設定（必要であればコメントを解除してカスタマイズ）
        // Jetstream::createUsersUsing(CreateNewUser::class);
        // Jetstream::useUserModel(User::class);
        // Jetstream::defaultApiTokenPermissions(['read', 'create', 'update']);


    }
}
