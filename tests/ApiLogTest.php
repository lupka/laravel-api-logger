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

        $response = $this->get('get?q1=data&q2=another');

        $this->assertDatabaseHas('api_logs', [
            'method' => 'GET',
            'url' => 'get',
            'status' => 200,
            'ip' => '127.0.0.1',
            'request_query_parameters' => '{"q1":"data","q2":"another"}',
            'response_body' => '{"method":"get"}',
        ]);
    }

    public function test_post_route_log()
    {
        Route::post('/post', TestApiController::class.'@post');

        $response = $this->postJson('post', [
            'param1' => 'test1',
            'param2' => 'test2',
        ]);

        $this->assertDatabaseHas('api_logs', [
            'method' => 'POST',
            'url' => 'post',
            'status' => 200,
            'ip' => '127.0.0.1',
            'request_body' => '{"param1":"test1","param2":"test2"}',
            'response_body' => '{"method":"post"}',
        ]);
    }
}
