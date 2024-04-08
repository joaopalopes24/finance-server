# 📌 Sunset Finance

This application was developed for a work challenge.

Laravel Ecosystem:

-   [Laravel Pint](https://github.com/laravel/pint)
-   [Laravel Sail](https://github.com/laravel/sail)
-   [Laravel Breeze](https://github.com/laravel/breeze)
-   [Laravel Sanctum](https://github.com/laravel/sanctum)

### 📋 Requirements

-   PHP v8.3

### 🔧 How to install

-   Clone the project

    ```bash
        git clone git@github.com:joaopalopes24/finance-server.git
    ```

-   Copy the .env.example file

    -   If using linux: cp .env.example .env
    -   If you are on windows, open the file in a code editor and save it again as .env

-   Installing Composer Dependencies using Docker

    ```bash
        docker run --rm \
            -u "$(id -u):$(id -g)" \
            -v "$(pwd):/var/www/html" \
            -w /var/www/html \
            laravelsail/php83-composer:latest \
            composer install --ignore-platform-reqs
    ```

-   You can configure a shell alias

    ```bash
        alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
    ```

-   Run the system using Docker and Sail

    ```bash
        sail up -d
    ```

-   Create a new key for the application

    ```bash
        sail artisan key:generate
    ```

-   Run Migrations with Seeders

    ```bash
        sail artisan migrate:fresh --seed
    ```

### 📦 Development Tools

-   Run Pest tests

    ```bash
        sail artisan test
    ```

-   Run Pest tests with Coverage

    ```bash
        sail artisan test --coverage
    ```

-   Run pint command to fix the code style of PHP files

    ```bash
        sail exec laravel.test ./vendor/bin/pint
    ```

## 🚀 Okay, good job!
