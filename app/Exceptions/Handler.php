<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

//    public function unauthenticated($request, AuthenticationException $exception)
//    {
//        // header部
//        $header = [
//            'request' => $request->path(),
//            'status'  => 'FAILED',
//            'status_code' => 401,
//            'messages' => ['認証に失敗しました'],
//        ];
//        // body部
//        $result = [
//            'header'   => $header,
//            'body'     => [],
//        ];
//
//        return $request->expectsJson()
//            ? response()->json($result, 401, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE)
//            : redirect()->guest($exception->redirectTo() ?? route('login'));
//    }
//
//    /**
//     * Render an exception into an HTTP response.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  \Throwable  $exception
//     * @return \Symfony\Component\HttpFoundation\Response
//     *
//     * @throws \Throwable
//     */
//    public function render($request, Throwable $exception)
//    {
//        if($exception instanceof \Illuminate\Session\TokenMismatchException) {
//
//            // header部
//            $header = [
//                'request' => $request->path(),
//                'status'  => 'FAILED',
//                'status_code' => 419,
//                'messages' => ['CSRF 検証に失敗したため、リクエストは中断されました'],
//            ];
//            // body部
//            $result = [
//                'header'   => $header,
//                'body'     => [],
//            ];
//
//            return response()->json(
//                $result,
//                419,
//                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
//                JSON_UNESCAPED_UNICODE
//            );
//        }
//
//        return parent::render($request, $exception);
//    }
}
