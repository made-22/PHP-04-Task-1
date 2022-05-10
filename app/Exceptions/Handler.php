<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->is('api/*')) {
            return $this->renderApiError($e);
        }

        return parent::render($request, $e);
    }

    /**
     * @param Throwable $e
     * @return JsonResponse
     */
    private function renderApiError(Throwable $e): JsonResponse
    {
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $errors = [];

        if ($e->getMessage()) {
            $errors['message'] = $e->getMessage();
        }

        if ($e instanceof ModelNotFoundException) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }

        if ($e instanceof AuthenticationException) {
            $statusCode = Response::HTTP_UNAUTHORIZED;
        }

        if ($e instanceof AuthorizationException) {
            $statusCode = Response::HTTP_FORBIDDEN;
        }

        if ($e instanceof ValidationException) {
            $statusCode = Response::HTTP_BAD_REQUEST;

            if (!isset($errors['validation'])) {
                $errors['validation'] = [];
            }

            $exceptionErrors = $e->errors();
            foreach ($exceptionErrors as $field => $message) {
                $errors['validation'][] = [
                    'field' => $field,
                    'message' => $message
                ];
            }
        }

        return response()->json(
            [
                'success' => false,
                'errors' => $errors
            ],
            $statusCode
        );
    }
}
