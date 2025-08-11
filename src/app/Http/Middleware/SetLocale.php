<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = array_keys(config('languages.supported'));
        $defaultLocale = config('languages.fallback', 'en');

        // 1. ルートパラメータからロケールを取得（例: /en, /ja）
        $locale = $request->route('locale');

        // 2. ルートにロケールがなければ、セッション、Cookie、ブラウザ設定、アプリのデフォルトから決定
        if (!$locale || !in_array($locale, $supportedLocales)) {
            $locale = Session::get('locale');
            if (!$locale || !in_array($locale, $supportedLocales)) {
                $locale = Cookie::get('app_locale');
                if (!$locale || !in_array($locale, $supportedLocales)) {
                    $locale = $request->getPreferredLanguage($supportedLocales);
                    if (!$locale || !in_array($locale, $supportedLocales)) {
                        $locale = $defaultLocale;
                    }
                }
            }
        }

        // 最終的に決定されたロケールをLaravelに設定
        App::setLocale($locale);
        Session::put('locale', $locale);
        Cookie::queue('app_locale', $locale, 60 * 24 * 30); // 30日間有効なCookie

        // PHPのOSロケールを設定
        $this->setPhpLocale($locale);

        return $next($request);
    }

    /**
     * PHPのOSロケールを設定します。
     * setlocale() の最初の引数には必ず整数定数 (LC_ALLなど) を渡します。
     */
    protected function setPhpLocale(string $locale): void
    {
        $phpLocalesMap = config('languages.php_locales');
        $candidates = $phpLocalesMap[$locale] ?? $phpLocalesMap[config('languages.fallback', 'en')];

        // LC_ALL を最初の引数として渡し、可変長引数でロケール文字列を展開します。
        // これにより、setlocale() に Request オブジェクトなどが誤って渡されることを防ぎます。
        setlocale(LC_ALL, ...$candidates);
    }
}