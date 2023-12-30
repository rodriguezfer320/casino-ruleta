# Utiliza la imagen oficial de PHP 8.3 FPM como base
FROM php:8.3-fpm

# Librerias de Linux
RUN apt-get update -y && \
    apt-get install -y \
        libssl-dev \
        libcurl4-openssl-dev \
        zlib1g-dev \
        libzip-dev \
        unzip

# Instala las extensiones de PHP necesarias mongodb
RUN docker-php-ext-install pdo pdo_mysql && \
    pecl install mongodb && \
    docker-php-ext-enable pdo_mysql mongodb

# Habilita la extensión de MongoDB en el archivo de configuración de PHP
RUN echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini

# Instala Composer
COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer

# Establece el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html

# Instala las dependencias del proyecto
RUN composer install

# Comando para ejecutar el servidor Laravel
CMD php artisan serve --host=0.0.0.0 --port=80