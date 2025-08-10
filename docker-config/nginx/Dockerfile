# 安定版 Nginx イメージをベースに使用
FROM nginx:stable

# 作業ディレクトリ（任意）
WORKDIR /etc/nginx

# Nginx 設定ファイルのコピー
COPY default.conf /etc/nginx/conf.d/default.conf

# Laravel のログ保存ディレクトリを作成しておく（念のため）
RUN mkdir -p /var/log/nginx \
    && touch /var/log/nginx/access.log /var/log/nginx/error.log
