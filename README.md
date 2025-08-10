# docker_languages
tree -L2
.
├── README.md
├── docker-compose.yml
├── docker-config/
│   ├── db/
│   │   ├── conf/my.cnf              # MariaDB の設定ファイル
│   │   └── sql/install.sql          # 初期データ投入用 SQL
│   ├── logs/                        # nginx や PHP のログ出力先
│   ├── nginx/
│   │   ├── Dockerfile               # Nginx のビルド用
│   │   └── default.conf             # Nginx の仮想ホスト設定
│   └── php/
│       ├── Dockerfile               # PHP + Laravel 実行環境のビルド用
│       └── php.ini                  # PHP 設定ファイル（開発向け）
└── src/
    └── index.php                    # Laravel またはシンプルなPHPコード


docker compose --env-file .env up -d --build

docker compose exec php bash
composer create-project laravel/laravel .

<!-- Jetstreamパッケージのインストール -->
composer require laravel/jetstream
<!-- Livewireを使用する場合 -->
php artisan jetstream:install livewire
<!-- Inertia.jsを使用する場合 -->
php artisan jetstream:install inertia
npm install
npm run build


# Laravelの.envファイル編集（DB設定）
nano .env

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

# 権限変更とマイグレーション
| コマンド           | 意味                                  | セキュリティ    |
| -------------- | ----------------------------------- | --------- |
| `chmod -R 775` | 所有者とグループに読み・書き・実行を許可。その他は読み・実行のみ許可。 | 安全寄り 🔒   |
| `chmod -R 777` | **すべてのユーザーに** 読み・書き・実行を許可。          | 危険（開発用）⚠️ |

docker compose exec php chown -R www-data:www-data storage bootstrap/cache
docker compose exec php chmod -R 777 storage bootstrap/cache


composer install
php artisan key:generate
php artisan migrate
npm install
npm run build

php artisan storage:link

キャッシュをクリア
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

exit

# 再ビルドと再起動
docker compose down
docker compose build --no-cache
docker compose up -d

# Laravelのstorageとbootstrap/cacheディレクトリに書き込み権限を付与
docker-compose exec php chmod -R 775 /var/www/storage
docker-compose exec php chmod -R 775 /var/www/bootstrap/cache
docker-compose exec php chmod -R 775 /var/www/agent/storage
docker-compose exec php chmod -R 775 /var/www/agent/bootstrap/cache
docker-compose exec php chmod -R 775 /var/www/languages/storage
docker-compose exec php chmod -R 775 /var/www/languages/bootstrap/cache

# 動作確認
open http://localhost
open http://localhost:8081  # phpMyAdmin

######
# https://laravel-lang.com/packages-lang.html
# https://github.com/Laravel-Lang/lang/blob/main/locales/ja/json.json
######

# Docker 内で実行（/var/www 配下）
docker compose exec php bash

# composer 再インストール
composer require laravel-lang/lang laravel-lang/publisher

# Laravel-Lang 本体
composer require laravel-lang/lang

# 翻訳ファイルパブリッシュ用
composer require laravel-lang/publisher

# 翻訳ファイルの追加（必要言語だけ指定）
php artisan lang:add ja en zh_CN zh_TW ko

# 翻訳ファイルを lang ディレクトリに公開
php artisan lang:publish

###
# https://vue-i18n.intlify.dev/guide/installation
# https://zenn.dev/blancpanda/articles/jetstream-vue-i18n
###
npm install vue-i18n@11

# composer.notes.md
- LaravelLang\Lang\ServiceProvider は lang 自動更新のために extra.laravel.providers に追加している。

https://zenn.dev/naopusyu/articles/86222755fb7168


Jetstream + Inertia インストール
composer require laravel/jetstream
php artisan jetstream:install inertia

推奨バージョンに依存関係を修正
npm uninstall vite @vitejs/plugin-vue
npm install vite@^6.0.0 @vitejs/plugin-vue@latest

フロントエンドビルド準備
npm install
npm run build

データベースマイグレーション
php artisan migrate


echo "# -" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/taidong5588/-.git
git push -u origin main



git remote add origin https://github.com/taidong5588/agent.git

git add . 
git branch -M main
git push -u origin main
