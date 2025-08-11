<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Support\Facades\Session; // ✅ 追加
use Illuminate\Support\Facades\Auth; // ✅ Authファサードを追加

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        // 🔥 ログインユーザーの言語設定を優先してロケールを決定
        $locale = optional($request->user())->language ?? Session::get('locale', config('app.locale'));

        // ✅ ユーザーの設定が異なっていれば更新
        if (Auth::check() && Auth::user()->language !== $locale) {
            Auth::user()->update(['language' => $locale]);
        }

        return [
            ...parent::share($request),
            
                'auth' => [
                    'user' => $request->user(),
                ],
                
                // 'locale' => fn () => app()->getLocale(),
                // 'fallback_locale' => fn () => config('app.fallback_locale'),

                // 🔥 i18nで使用するロケールとフォールバックロケールを共有
                'locale' => fn () => $locale,
                
                // ✅ Ziggyの設定を追加
                'ziggy' => fn () => [
                    (new Ziggy)->toArray(),
                    'location' => $request->url(),
                ],               
        ];
    }
}
