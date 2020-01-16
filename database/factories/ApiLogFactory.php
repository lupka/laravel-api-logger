<?php

use Faker\Generator as Faker;

$factory->define(\Lupka\ApiLogger\Models\ApiLog::class, function (Faker $faker) {
    return [
        'method' => 'GET',
        'url' => 'get',
        'status' => 200,
        'ip' => '127.0.0.1',
        'request_query_parameters' => '{"q1":"data","q2":"another"}',
        'response_body' => '{"method":"get"}',
        'user_agent' => 'Chrome',
    ];
});
