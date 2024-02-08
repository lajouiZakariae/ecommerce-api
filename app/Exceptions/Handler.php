<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
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

        $this->renderable(function (NotFoundHttpException $e) {

            if ($e->getStatusCode() === 404 && request()->accepts("application/json"))
                return response("", Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (MethodNotAllowedException $e) {
            if (request()->accepts("application/json"))
                return response("", Response::HTTP_METHOD_NOT_ALLOWED);
        });
    }
}
