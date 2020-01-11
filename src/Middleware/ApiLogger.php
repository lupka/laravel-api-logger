<?php

namespace Lupka\ApiLog\Middleware;

use Closure;
use Lupka\ApiLog\Models\ApiLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as GenericResponse;

class ApiLogger
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $data = [
            'request_body' => $request->getContent(),
            'request_query_parameters' => json_encode($request->query()),
            'method' => $request->method(),
            'url' => $request->path(),
            'user_id' => $request->user()->id ?? null,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'status' => $response->status(),
        ];

        // add all content if its a JsonResponse
        // can't do this for other response types (too big)
        if(get_class($response) == JsonResponse::class) {
            $data['response_body'] = $response->content();
        }

        if($response->exception) {
            $data['exception_type'] = get_class($response->exception);
            $data['exception_message'] = $response->exception->getMessage();
        }

        ApiLog::create($data);
    }
}
