<?php

namespace Tests\Feature\Api\V1\ShortLink;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShortLinkCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_create_links(): void
    {
        $requestData = [
            [
                'long_url' => 'https://www.google.com/',
                'title' => 'test',
                'tags' => [
                    'tag1',
                    'tag2'
                ]
            ],
            [
                'long_url' => 'https://laravel.com/',
                'title' => 'test2',
                'tags' => [
                    'tag3',
                    'tag4'
                ]
            ]
        ];

        $response = $this->postJson(route('links.store'), $requestData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment(['success' => true])
            ->assertJsonFragment(['count' => 2])
            ->assertJsonStructure([
                'success',
                'links' => [
                    '*' => [
                        'id',
                        'long_url',
                        'short_url'
                    ]
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_validation_request_with_wrong_long_url(): void
    {
        $requestData = [
            [
                'long_url' => 'https://www.not_exist_host.com/',
                'title' => 'test',
                'tags' => [
                    'tag1',
                    'tag2'
                ]
            ],
            [
                'long_url' => 'https://laravel.com/',
                'title' => 'test2',
                'tags' => [
                    'tag3',
                    'tag4'
                ]
            ]
        ];

        $response = $this->postJson(route('links.store'), $requestData);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonFragment(['success' => false])
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
            ]);
    }
}
