<?php

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Http\Kernel;

use Lupka\ApiLog\Middleware\ApiLogger;
use Lupka\ApiLog\ApiLogServiceProvider;
use Lupka\ApiLog\Tests\Fixtures\TestApiController;

class ApiLogTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function getPackageProviders($app)
    {
        return [
            ApiLogServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // add middleware to all routes for testing
        $app->make(Kernel::class)->prependMiddleware(ApiLogger::class);
    }

    public function test_get_route_log()
    {
        Route::get('/get', TestApiController::class.'@get');

        $response = $this->get('get');
        $response->assertStatus(200);
        $response->assertJson(['method' => 'get']);

        $this->assertDatabaseHas('api_logs', [
            'method' => 'GET',
            'url' => 'get',
            'response_body' => '{"method":"get"}',
        ]);
    }

}
