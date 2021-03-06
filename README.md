# Log API requests/responses inside your Laravel app

By default, this package logs the following data:

* Method (`GET`, `POST`, etc)
* URL
* Client IP Address
* Client User Agent
* Request Body
* Request Query Parameters
* User ID (for authenticated requests)
* HTTP Status Code (`200`, `404`, etc)
* Response Body (if JSON)
* Exception Type/Message if an error occurs

for each route where the middleware is installed.

## Installation

You can install the package via Composer:

```bash
composer require lupka/laravel-api-logger
```

The package will automatically register its service provider.

Then run the migration to add the `api_logs` table to your database:

```bash
php artisan migrate
```

Publish the config file (`api_logger.php`):

`php artisan vendor:publish --provider="Lupka\ApiLogger\ApiLoggerServiceProvider" --tag="config"`

Now you can install the `api-logger` middleware as needed. For example, in `app/Http/Kernel.php` (to log every API request):

```php
protected $middlewareGroups = [
    ...
    'api' => [
        ...
        'api-logger',
    ],
];
```

Or, you can add the alias to a specific route or group:

```php
Route::post('/test', 'TestController@test')->middleware('api-logger');
```

## Viewing Logs

Logs are stored in the `api_logs` table. There's an Eloquent model included in the package (`Lupka\ApiLogger\Models\ApiLog`) for querying records, etc. For example, to get all logs:

```php
Lupka\ApiLogger\Models\ApiLog::all();
```

## Clearing Logs

Logs can be cleared by scheduling the `api-logger:clear` job in your `app/Console/Kernel.php` file:

```php
protected function schedule(Schedule $schedule)
{
    ...
    $schedule->command('api-logger:clear')->daily();
}
```

Any logs older than 30 days will be cleared by default. This can be changed by modifying the `log_expiry` config value.

## Log -> User Relationship

The `ApiLog` Eloquent model contains a relationship (`$log->user()`) with the default `App\User` model. If your user model has a different class name, you can change that relationship using the `user_class` config value.

## License

Licensed under the MIT license. See [License File](LICENSE) for more information.
