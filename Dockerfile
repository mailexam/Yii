FROM yiisoftware/yii2-php:8.2-apache

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

RUN mkdir -p runtime web/assets \
    && chown -R www-data:www-data runtime web/assets \
    && chmod -R ug+rwX runtime web/assets

EXPOSE 80
