# Log API requests/responses inside your Laravel app

## Installation

You can install the package via Composer:

```bash
composer require lupka/laravel-api-log
```

The package will automatically register its service provider.

Then run the migration to add the `api_logs` table to your database:

```bash
php artisan migrate
```

Now you can install the `ApiLogger` as needed. For example, in `app/Http/Kernel.php` (to log every API request):

```php
protected $middlewareGroups = [
    ...
    'api' => [
        ...
        \Lupka\ApiLog\Middleware\ApiLogger::class,
    ],
];
```

Or, you can add the alias to any route or group you want to log:
