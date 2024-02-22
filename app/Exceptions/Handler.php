<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
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
        $this->reportable(function (BusinessException $e) {
        });

        $this->renderable(function (ResourceNotFoundException $e) {
            if (request()->acceptsJson()) {
                return response()
                    ->json(["message" => $e->getMessage()], Response::HTTP_NOT_FOUND);
            }
        });

        $this->renderable(function (BusinessException $e) {
            if ($e instanceof ResourceNotCreatedException && request()->accepts("application/json"))
                return response()
                    ->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        });
    }
}
