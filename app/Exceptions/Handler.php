<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();

            if ($statusCode == 404) {
                return response()->view('errors.404', [], 404);
            } elseif ($statusCode == 403) {
                return response()->view('errors.403', [], 403);
            } else if  ($statusCode == 401) {
                return response()->view('errors.401', [], 401);
            }else {
                return response()->view('errors.500', [], 500);
            }
        }

        return parent::render($request, $exception);
    }
}
