FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Obtener la última versión de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Crear usuario del sistema para ejecutar comandos de Composer y Artisan
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copiar código existente de la aplicación
COPY --chown=www:www . /var/www

# Cambiar el directorio de trabajo actual
WORKDIR /var/www

# Dar permisos correctos a los directorios
RUN chown -R www:www /var/www
RUN chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Cambiar al usuario www
USER www

# Instalar dependencias de Composer
RUN composer install --optimize-autoloader --no-dev

# Instalar dependencias de npm y construir assets
RUN npm ci
RUN npm run build

# Limpiar dependencias de desarrollo de npm (opcional)
RUN npm prune --production

# Exponer puerto 9000 y iniciar servidor php-fpm
EXPOSE 9000
CMD ["php-fpm"]