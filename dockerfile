FROM php:8.2-cli


RUN apt-get clean && rm -rf /var/lib/apt/lists/* && apt-get update -y --fix-missing && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www

COPY . .


RUN composer install


RUN cp .env.example .env && \
    php artisan key:generate && \
    touch database/database.sqlite && \
    php artisan migrate --force


EXPOSE 8000


CMD php artisan serve --host=0.0.0.0 --port=8000