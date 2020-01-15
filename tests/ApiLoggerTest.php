<?php

namespace Lupka\ApiLogger\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Http\Kernel;

use Lupka\ApiLogger\Models\ApiLog;
use Lupka\ApiLogger\Middleware\ApiLogger;
use Lupka\ApiLogger\ApiLogServiceProvider;

use Lupka\ApiLogger\Tests\Fixtures\User;
use Lupka\ApiLogger\Tests\Fixtures\TestApiController;

class ApiLoggerTest extends TestCase
{
    public function getEnvironmentSetUp($app)
    {
        // add middleware to all routes for testing
        $app->make(Kernel::class)->prependMiddleware(ApiLogger::class);

        parent::getEnvironmentSetUp($app);
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

    public function test_404_route_log()
    {
        $response = $this->postJson('nope');

        $this->assertDatabaseHas('api_logs', [
            'method' => 'POST',
            'url' => 'nope',
            'status' => 404,
            'ip' => '127.0.0.1',
            'exception_type' => 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException',
        ]);
    }

    public function test_500_exception_route_log()
    {
        Route::post('/exception', TestApiController::class.'@exception');

        $response = $this->postJson('exception', [
            'param1' => 'test1',
            'param2' => 'test2',
        ]);

        $this->assertDatabaseHas('api_logs', [
            'method' => 'POST',
            'url' => 'exception',
            'status' => 500,
            'ip' => '127.0.0.1',
            'request_body' => '{"param1":"test1","param2":"test2"}',
            'exception_type' => 'Symfony\Component\Debug\Exception\FatalThrowableError',
            'exception_message' => 'Call to undefined method Lupka\ApiLogger\Tests\Fixtures\TestApiController::exception()',
        ]);
    }

    public function test_complex_url_route_log()
    {
        Route::get('/url/parameter/{param}', TestApiController::class.'@param');

        $response = $this->get('/url/parameter/999');

        $this->assertDatabaseHas('api_logs', [
            'url' => 'url/parameter/999',
            'response_body' => '{"param":"999"}',
        ]);
    }

    public function test_user_is_tracked_and_attached_to_log()
    {
        $this->loadLaravelMigrations();

        $user = User::create([
            'email' => 'test@test.com',
            'name' => 'test',
            'password' => 'password'
        ]);

        Route::get('/get', TestApiController::class.'@get');

        $response = $this->actingAs($user)
                         ->get('get');

        $this->assertDatabaseHas('api_logs', [
            'method' => 'GET',
            'url' => 'get',
            'user_id' => $user->id,
        ]);
    }
}
