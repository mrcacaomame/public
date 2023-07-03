# ベースイメージの指定
FROM ubuntu:latest

# apt-getがインタラクティブなフロントエンドを使用しないように設定
ENV DEBIAN_FRONTEND=noninteractive

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    ca-certificates \
    unzip \
    zip \
    git \
    mysql-client \
    apache2 \
    php8.1 \
    php8.1-mysql \
    php8.1-exif \
    php8.1-bcmath \
    php8.1-gd \
    php8.1-mbstring \
    php8.1-xml \
    php-json \
    php8.1-curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Laravelのインストール
WORKDIR /var/www
RUN composer global require laravel/installer && composer clear-cache
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# アプリケーションのコピーと依存パッケージのインストール
COPY . /var/www
RUN composer install --no-interaction --no-dev --prefer-dist \
   && chown -R www-data:www-data /var/www \
   && chmod -R 755 /var/www/storage

# MySQLの設定
ENV MYSQL_ROOT_PASSWORD=rootroot

# コンテナ内で実行するコマンドの指定
# CMD php artisan serve --host=0.0.0.0 --port=8000
