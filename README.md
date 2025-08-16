# Schichtplan

This is a very simple shift planer. An admin can define a plan with shifts and users can subscribe to one or more shifts. It can help to organize parties, festivals or political events. 

## About Schichtplan

Schichtplan was original developed by [o](https://code.immerda.ch/o) with [cakephp](https://book.cakephp.org/1.3/en/index.html) framework back in 2011. The current version uses [laravel](https://github.com/laravel/framework) as a base and is compatible with modern php versions (\^7.3|\^8.0). 

This fork was created in 2025 for the [Rock AG e.V.](https://rockag.net/) organization and is currently maintained by [winterch](https://github.com/winterch).

## Setup with laradock (recommended)

The application can be set up in Docker containers using [laradock](https://laradock.io/).

### Configuration (Laravel)

In the base directory create a `.env` file. E.g.:
```
cp .env.example .env
```
In `.env` set the values for `MAIL_FROM_ADDRESS` and `BASIC_AUTH_USERS`. `BASIC_AUTH_USERS` is a comma-separated list of `username:password` pairs.

For more information on `.env` configuration options please refer to the official Laravel and Symfony documentation.

### Configuration (laradock)

Change into the laradock directory (e.g. ``cd laradock``) and create a `.env` file for laradock. E.g.:
```
cp .env.example .env
```

### Additional locales (optional)
If necessary, enable additional locales in the php-fpm container from laradock. In `.env` of the laradock folder change the following values:
- `PHP_FPM_INSTALL_ADDITIONAL_LOCALES`: Set to `true`
- `PHP_FPM_ADDITIONAL_LOCALES`: Add the desired locales (e.g. `de_DE.UTF-8`) to the list
- `PHP_FPM_DEFAULT_LOCALE`: Set to the desired default locale

### Run the laradock containers
Make sure Docker Compose is installed and up-to-date and Docker is running.

Inside the `laradock` folder:

- Build and start containers:
    ```
    docker-compose up -d caddy mysql mailhog workspace
    ```

### Install dependencies and run commands using the `workspace` container

*Make sure that you run all commands that require PHP oder Node.js inside the `workspace` container. Otherwise you might run into issues because of different software versions.*

- Enter the laradock `workspace` container:
    ```
    docker-compose exec --user=laradock workspace bash
    ```
    The `--user=laradock` option makes sure that all files are created with your native user and group instead of the docker user.

- Install node.js dependencies and compile assets
    ```
    npm install && npm run dev
    ```

- Install Laravel dependencies
    ```
    composer install
    ```

- Generate app key
    ```
    php artisan key:generate
    ```
    The new app key will be automatically written into `.env`

- Run database migrations
    ```
    php artisan migrate
    ```

The application should now be reachable at https://localhost. You have to allow your browser to trust the self-signed certificate of the Caddy server. This is only necessary for local setup.

### Register Cronjob (optional)
On a production system or for testing purposes you should register a cronjob to cleanup plans without activity. For more information see the [laravel documentation](https://laravel.com/docs/8.x/scheduling) 
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1```
```

## Installation (without laradock)

__This setup is not maintained, use at your own risk__

You need to install the dependencies to run schichtplaner.
```bash
./setup.sh
```


## Upgrade
There is no upgrade path from older version of schichtplan (< 2.0).

## Commands
There is a command to clean up old plans. Most of the time you want to run this in a schedule and don't need to invoke it directly.
```bash
php artisan schichtplan:cleanup
```

There is a command to send notification emails to subscribers one day before the event.
```bash
php artisan schichtplan:notify-subscribers
```

## Development
If you find errors please open an issue or send a pull request!

__If you followed the laradock setup, you don't need this section, since laradock will provide the dev server__

To start devloping, clone the repo, install the dependencies and copy the `.env.example` to .env. You want to check the values in the `.env` file, before starting to develop.

You need the frontend dependencies as well.
```bash
# install frontedn dependencies (CSS/Bootstrap/JS)
npm install
# Build frontend assets
npm run dev
```

```bash
# run dev server
php artisan serve
```

If you change the design make sure you also commit the built assets.
```bash
# Build production assets
npm run prod
```

## Containerized development env (deprecated)

__This setup is not maintained, use at your own risk. We recommend the setup via laradock described above__

```bash
./setup.sh
CR=podman
$CR run --rm --env-file=.env --net=host -p 8000:8000 -it -v .:/app docker.io/library/php:8 bash -c "cd /app && php artisan serve"
```

## License

Schichtplan is free software and under [AGPL license](https://www.gnu.org/licenses/agpl-3.0.en.html)

[Laravel](https://laravel.com) is Opensource software and under [MIT-License](https://opensource.org/licenses/MIT).

[Flatpickr](https://opensource.org/licenses/MIT) is opensource software and under [MIT_license](https://opensource.org/licenses/MIT)

[Tailwindcss](https://github.com/tailwindlabs/tailwindcss) is opensource software and under [MIT_license](https://opensource.org/licenses/MIT)
