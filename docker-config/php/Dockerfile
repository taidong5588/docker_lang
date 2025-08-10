# ============================================
# Stage 1: Composer の取得（軽量ビルド用）
# ============================================
FROM composer:latest AS composer

# ============================================
# Stage 2: PHP + Node.js + Laravel 環境構築
# ============================================
FROM php:8.3-fpm

# 作業ディレクトリを Laravel 向けに設定
WORKDIR /var/www/src

# 必要なシステムパッケージと PHP 拡張モジュールをインストール
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        zip \
        unzip \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libicu-dev \
        ca-certificates \
        gnupg \
    # PHP 拡張モジュールをインストール
    && docker-php-ext-install -j$(nproc) \
        intl \
        mbstring \
        pdo \
        pdo_mysql \
        zip \
        bcmath \
    # Node.js（最新版）のセットアップ（v20対応）
    && curl -fsSL https://deb.nodesource.com/setup_current.x -o nodesource_setup.sh \
    && bash nodesource_setup.sh \
    && apt-get install -y --no-install-recommends nodejs \
    # 不要なキャッシュ・一時ファイルを削除して軽量化
    && apt-get purge -y curl gnupg \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* nodesource_setup.sh

# Node.js / npm のバージョン確認（任意）
RUN node -v && npm -v

# Stage 1 から Composer をコピー
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# PHP 設定ファイルをコピー（開発用）
COPY php.ini /usr/local/etc/php/php.ini

# PHP エラーログ用ディレクトリを作成
RUN mkdir -p /var/log/php-fpm/errors

# Composer 環境変数設定
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/root/.config/composer \
    PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

# Laravel インストーラをグローバルにインストール
RUN composer global require laravel/installer && composer clear-cache

# www ユーザー・グループを作成して権限付与
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www && \
    chown -R www:www /var/www

# 実行ユーザーを www に変更
USER www

# PHP-FPM のポートを公開
EXPOSE 9000

# コンテナ起動時のデフォルトコマンド
CMD ["php-fpm"]
