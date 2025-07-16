FROM php:8.2-cli

WORKDIR /app
COPY . .

# Install SQLite and PHP extensions
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev unzip libzip-dev && \
    docker-php-ext-install pdo pdo_sqlite

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
