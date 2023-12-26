# Utiliza la imagen oficial de PHP 8.3 CLI como base
FROM php:8.3-cli

# Librerias de Linux
RUN apt-get update -y && \
    apt-get install -y \
        libzip-dev \
        unzip \
        zip \
        zlib1g-dev \
        libcurl4-openssl-dev \
        pkg-config \
        libssl-dev

# Instala las extensiones de PHP necesarias
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo_mysql

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