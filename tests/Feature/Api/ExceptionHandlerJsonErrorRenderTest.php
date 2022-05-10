<?php

namespace Tests\Feature\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ExceptionHandlerJsonErrorRenderTest extends TestCase
{
    /**
     * @return void
     */
    public function test_not_found_error(): void
    {
        $response = $this->getJson('/api/v1/not-found-error-test');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'success',
                'errors'
            ])
            ->assertJson(['success' => false]);
    }

    public function test_logical_error(): void
    {
        Route::get('/api/v1/logical-error-test', function () {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        });

        $response = $this->getJson('/api/v1/logical-error-test');

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJsonStructure([
                'success',
                'errors'
            ])
            ->assertJson(['success' => false]);
    }

    public function test_model_not_found_error(): void
    {
        Route::get('/api/v1/model-not-found-error-test', function () {
            throw new ModelNotFoundException();
        });

        $response = $this->getJson('/api/v1/model-not-found-error-test');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'success',
                'errors'
            ])
            ->assertJson(['success' => false]);
    }

    public function test_request_validation_error(): void
    {
        Route::get('/api/v1/validation-error-test', function () {
            throw ValidationException::withMessages([
                'error_field' => ['error_field message']
            ]);
        });

        $response = $this->getJson('/api/v1/validation-error-test');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure([
                'success',
                'errors' => [
                    'message',
                    'validation' => [
                        '*' => [
                            'field',
                            'message'
                        ]
                    ]
                ]
            ])
            ->assertJson(['success' => false]);
    }

    public function test_unauthorized_error(): void
    {
        Route::get('/api/v1/unauthorized-error-test')->middleware('auth');

        $response = $this->getJson('/api/v1/unauthorized-error-test');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'success',
                'errors' => [
                    'message',
                ]
            ])
            ->assertJson(['success' => false]);
    }

    public function test_forbidden_error(): void
    {
        Route::get('/api/v1/forbidden-error-test', function () {
            throw new AuthorizationException();
        });

        $response = $this->getJson('/api/v1/forbidden-error-test');

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure([
                'success',
                'errors'
            ])
            ->assertJson(['success' => false]);
    }
}
