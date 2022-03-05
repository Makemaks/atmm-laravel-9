<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use \Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use BadMethodCallException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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
        /*
        $this->reportable(function (Throwable $e) {
            //
        });
        */

        $this->renderable(function (BadMethodCallException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                  'status' => 'error',
                  'error' => $e->getMessage(),
                ], 500);
            }
        });


        $this->renderable(function (FatalErrorException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                  'status' => 'error',
                  'error' => $e->getMessage(),
                ], 500);
            }
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                  'error' => $e->getMessage(),
                ], 401);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                  'status' => 'error',
                  'error' => str_replace(array('[App\\Models\\', ']'), '', $e->getMessage()),
                ], 404);
            }
        });

        $this->renderable(function (HttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                  'status' => 'error',
                  'error' => $e->getMessage(),
                ], 403);
            }
        });


        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                  'status' => 'error',
                  'error' => str_replace(array('[App\\Models\\', ']'), '', $e->getMessage()),
                ], 404);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'error' => $e->getMessage(),
                ], 405);
            }
        });

    }
}
