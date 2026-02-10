# ---------- Node / Vite build ----------
FROM node:20-alpine AS node
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.* .
COPY postcss.config.* .
COPY tailwind.config.* .

RUN npm run build


# ---------- PHP / Laravel ----------
FROM php:8.4
WORKDIR /app

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    bcmath \
    sockets \
    opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy backend files first
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

# Overlay built frontend assets
COPY --from=node /app/public/build ./public/build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache
