FROM php:8.4-apache


# Paquets système + extensions PHP
RUN apt-get update && apt-get install -y \
    bash \
    git \
    curl \
    unzip \
    zlib1g-dev \
    libzip-dev \
 && docker-php-ext-install pdo pdo_mysql zip \
 && rm -rf /var/lib/apt/lists/*

# Activer mod_rewrite (très souvent nécessaire pour Laravel / frameworks)
RUN a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www/html

# Exposer Apache
EXPOSE 80

# Apache est déjà lancé par l’image de base → PAS de CMD
