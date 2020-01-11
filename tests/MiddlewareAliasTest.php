<?php

namespace Lupka\ApiLog\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Http\Kernel;

use Lupka\ApiLog\Middleware\ApiLogger;
use Lupka\ApiLog\ApiLogServiceProvider;
use Lupka\ApiLog\Tests\Fixtures\TestApiController;

class MiddlewareAliasTest extends TestCase
{
    public function test_request_not_logged_without_middleware_call()
    {
        Route::get('/middleware', TestApiController::class.'@get');

        $response = $this->get('middleware');

        $this->assertDatabaseMissing('api_logs', [
            'url' => 'middleware',
        ]);
    }

    public function test_request_is_logged_when_middleware_attached_to_route()
    {
        Route::get('/middleware', TestApiController::class.'@get')->middleware('api-logger');

        $response = $this->get('middleware');

        $this->assertDatabaseHas('api_logs', [
            'url' => 'middleware',
        ]);
    }
}
