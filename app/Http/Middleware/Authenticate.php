<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // header部
        $header = [
            'request' => $request->path(),
            'status'  => 'FAILED',
            'status_code' => 401,
            'messages' => ['認証に失敗しました'],
        ];
        // body部
        $result = [
            'header'   => $header,
            'body'     => [],
        ];

        $res = response()->json(
            $header,
            422,
            [
                'Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE
        );
        throw new HttpResponseException($res);
        return $request->expectsJson();

//        if (! $request->expectsJson()) {
//            return route('unauthenticated', ['path' => $request->path()]);
//        }
    }
}
