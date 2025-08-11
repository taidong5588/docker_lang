<?php
// config/languages.php
return [
    // サポートされているロケールとその表示名
    // 例: 'ja' => '日本語' (言語選択などで使用)
    'supported' => [
        'ja' => '日本語',
        'en' => 'English',
        'ko' => '한국어',
        'zh' => '中文（简体）',
    ],

    // 短縮ロケールキーをPHPのsetlocale()で使用するシステムロケール文字列にマッピング
    // 環境によってこれらの文字列は異なる場合があるため、適切なものを設定してください。
    // 例: Ubuntu/Debianでは 'ja_JP.UTF-8'、Windowsでは 'Japanese_Japan.932' など
    'php_locales' => [
        'ja' => ['ja_JP.UTF-8', 'ja_JP', 'Japanese_Japan.932'],
        'en' => ['en_US.UTF-8', 'en_US', 'English_United States.1252'],
        'ko' => ['ko_KR.UTF-8', 'ko_KR', 'Korean_Korea.949'],
        'zh' => ['zh_CN.UTF-8', 'zh_CN', 'Chinese_China.936'],
    ],

    // フォールバックロケール (予期せぬ問題が発生した場合のデフォルト)
    'fallback' => env('APP_FALLBACK_LOCALE', config('app.fallback_locale', 'ja')),
];