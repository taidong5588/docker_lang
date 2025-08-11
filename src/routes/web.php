<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Laravel\Jetstream\Features; // Jetstream特有の機能

// ルートURLへのアクセス時に、セッションまたはブラウザ設定に基づいてリダイレクト
Route::get('/', function (Request $request) {
    $locale = Session::get('locale', $request->getPreferredLanguage(array_keys(config('languages.supported'))) ?? config('languages.fallback', 'en'));
    return redirect()->to("/{$locale}");
});

// 全てのWebルートを {locale} でプレフィックスし、SetLocaleミドルウェアを適用
Route::prefix('{locale}')
    ->where(['locale' => implode('|', array_keys(config('languages.supported')))])
    ->group(function () {
        // JetstreamのWelcomeページ（認証済みでない場合）
        Route::get('/', function () {
            return Inertia::render('Welcome', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
                'status' => session('status'),
            ]);
        })->name('welcome');

        // Jetstreamの認証済みルートグループ
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->group(function () {
            Route::get('/dashboard', function () {
                return Inertia::render('Dashboard');
            })->name('dashboard');

            // Jetstreamのプロファイル管理ページ
            // Route::get('/user/profile', function () {
            //     return Inertia::render('Profile/Show', [
            //         'confirmsTwoFactorAuthentication' => Features::enabled(Features::twoFactorAuthentication()),
            //         'canUpdatePassword' => Features::enabled(Features::updatePasswords()),
            //         'canManageTwoFactorAuthentication' => Features::enabled(Features::twoFactorAuthentication()),
            //         'canSetPassword' => Features::enabled(Features::setPasswords()),
            //         'canDeleteUsers' => Features::enabled(Features::deleteAccount()),
            //     ]);
            // })->name('profile.show');

            // JetstreamのAPIトークン管理ページ
            if (Features::hasApiFeatures()) {
                Route::get('/user/api-tokens', function () {
                    return Inertia::render('ApiTokens/Index');
                })->name('api-tokens.index');
            }

            // Jetstreamのチーム管理ページ
            if (Features::hasTeamFeatures()) {
                Route::get('/teams/create', function () {
                    return Inertia::render('Teams/Create');
                })->name('teams.create');

                Route::get('/teams/{team}', function () {
                    return Inertia::render('Teams/Show');
                })->name('teams.show');

                Route::get('/teams/{team}/members', function () {
                    return Inertia::render('Teams/Show');
                })->name('team-members.show');
            }
        });

        // 言語切り替え用のPOSTエンドポイント (URL変更をトリガーするだけなので必須ではないが、明示的)
        Route::post('/language/switch/{newLocale}', function (Request $request, $locale, $newLocale) {
            $supported = array_keys(config('languages.supported'));
            if (!in_array($newLocale, $supported)) {
                return response()->json(['message' => 'Unsupported locale'], 400);
            }
            Session::put('locale', $newLocale);
            return response()->json(['message' => 'Locale updated', 'new_locale' => $newLocale]);
        })->name('language.switch');
    });