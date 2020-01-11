<?php

namespace Lupka\ApiLog\Tests\Fixtures;

class TestApiController
{
    public function get()
    {
        return ['method' => 'get'];
    }

    public function post()
    {
        return ['method' => 'post'];
    }

    public function param($param)
    {
        return ['param' => $param];
    }
}
