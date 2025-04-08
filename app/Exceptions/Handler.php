<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
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

    protected function unauthenticated($request, AuthenticationException $exception)  {

        if ($request->expectsJson()) {
            // Log::info($request->header());
            // Log::info('inside $request->header()');
            // Log::info($request->all());
            // Log::info(' inside $request->all()');
            return response()->json(ErrorResponse("api.unauthenticated"), 200);
        }
        // Log::info($request->header());
        //     Log::info('outside $request->header()');
        // Log::info($request->all());
        // Log::info('outside $request->all()');

        return redirect()->guest(route("backend.login"));
    }
}
