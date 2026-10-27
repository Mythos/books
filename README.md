# Mythos' Book Inventory System

This project is a little helper to keep track of my personal manga collection. It allows me to save series with their ISBNs and publish dates and set a status for each book. (New, Ordered or Delivered)

I also use this project to improve my Laravel skills as I'm mainly a .NET developer. So please expect bugs, bad code style and probably some hacks.

## Installation

* Install PHP 8.5.12 and the extensions listed in the [Laravel 13 requirements](https://laravel.com/docs/13.x/deployment#server-requirements).
* Clone this repository
* Run `composer install --no-dev`
* Set your credentials and settings in `.env`
* Run `php artisan migrate`
* Set up the `DocumentRoot` correctly (points to `/public/` folder)
* As series covers are stored publicly run 
    * `php artisan storage:link`
    *  or `ln -s ./storage/app/public ./public/storage` if you are on a shared webspace

## Docker Development

The repository includes a Docker Compose setup that can replace a local Laragon/XAMPP-style installation. It provides PHP-FPM, Nginx, MariaDB, Redis, Mailpit and a Node container for Vite assets. The normal Laravel `.env` file is also used by Docker Compose; `.env.example` contains separate comment blocks for Laravel application settings and Docker development settings.

1. Copy `.env.example` to `.env` and adjust values if needed.
2. Start the runtime services:

    ```sh
    docker compose up -d --build
    ```

3. Install PHP dependencies and initialize Laravel:

    ```sh
    docker compose exec app composer install
    docker compose exec app php artisan key:generate
    docker compose exec app php artisan migrate
    docker compose exec app php artisan storage:link
    ```

4. Build frontend assets once:

    ```sh
    docker compose run --rm node sh -c "corepack enable pnpm && pnpm install --frozen-lockfile"
    docker compose run --rm node sh -c "corepack enable pnpm && pnpm run prod"
    ```

For continuous frontend rebuilds, run:

```sh
docker compose --profile assets up node
```

The application is available at <http://localhost:8080>. Mailpit is available at <http://localhost:8025>. MariaDB is exposed on `localhost:3306` with database/user/password `books`/`books`/`books`, and Redis is exposed on `localhost:6379`.

Useful commands:

```sh
docker compose exec app php artisan test
docker compose exec app vendor/bin/phpunit
docker compose exec app php artisan tinker
docker compose down
```

### Upgrading an existing database

The Compose stack uses MariaDB 13.0.2 and enables `MARIADB_AUTO_UPGRADE` so the image runs `mariadb-upgrade` when an existing database requires it. Before starting a newer major version, stop application writes, make a database dump and preserve a cold copy of the MariaDB volume. The image's automatic backup covers the system database only; it does not replace an application-data backup.

Keep the previous image digest and the pre-upgrade backup until the application has been checked. Do not start an older MariaDB image against an upgraded data directory. For rollback, use the old image with the preserved pre-upgrade volume or restore the dump into a separate compatible instance. MariaDB 13.0 is a rolling release; continue tracking its support window when updating the stack.

## Contribution

You are very welcome to create a Pull Request. Please consider using proper commit messages describing the change (i.e. not only `fix` or something like that) and assign me as a reviewer.
If you want to bring in new features, please create an issue for discussion first.

## License

MIT

## Screenshots

### Landing page & Gallery

![Screenshot 2022-05-29 at 10-06-09 Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/170858541-f8f696e1-540c-424c-90df-5f3dc727d85a.png)

### Series details page

![Screenshot 2022-05-29 at 10-06-19 More than a Doll - Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/170858547-117a7906-36ee-4e50-83f4-398850120e7d.png)

### Statistics

![Screenshot 2022-05-29 at 10-06-26 Statistiken - Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/170858550-a48e81d1-b91e-4601-8874-51ed682882ed.png)
