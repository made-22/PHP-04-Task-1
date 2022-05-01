<?php

namespace Tests\Feature\Api\V1\ShortLink;

use App\Models\ShortLink;
use App\Services\ShortLink\ShortLinkGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ShortLinkIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_get_links(): void
    {
        ShortLink::factory(20)->create();

        $response = $this->getJson(route('links.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['success' => true])
            ->assertJsonFragment(['count' => 20])
            ->assertJsonStructure([
                'links' => [
                    '*' => [
                        'id',
                        'long_url',
                        'short_url',
                        'title',
                        'tags',
                    ]
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_get_links_with_offset_limit(): void
    {
        $shortLinkIdFirst = resolve(ShortLinkGeneratorService::class)->generate();
        $shortLinkIdSecond = resolve(ShortLinkGeneratorService::class)->generate();

        ShortLink::factory()->createMany([
            [
                'id' => $shortLinkIdFirst
            ],
            [
                'id' => $shortLinkIdSecond
            ]
        ]);

        $response = $this->getJson(route('links.index', [
                'offset' => 1,
                'limit' => 1
            ])
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['success' => true])
            ->assertJsonFragment(['count' => 1])
            ->assertJsonFragment(['id' => $shortLinkIdSecond])
            ->assertJsonStructure([
                'links' => [
                    '*' => [
                        'id',
                        'long_url',
                        'short_url',
                        'title',
                        'tags',
                    ]
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_get_links_with_filter_by_tag(): void
    {
        ShortLink::factory()->createMany([
            [
                'tags' => ['test_filter_by_tag1', 'tag2']
            ],
            [
                'tags' => ['tag1', 'tag2']
            ],
        ]);

        $response = $this->getJson(route('links.index', [
                'tag' => 'st_filter_'
            ])
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['success' => true])
            ->assertJsonFragment(['count' => 1])
            ->assertJsonStructure([
                'links' => [
                    '*' => [
                        'id',
                        'long_url',
                        'short_url',
                        'title',
                        'tags',
                    ]
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_get_links_with_filter_by_title(): void
    {
        ShortLink::factory()->createMany([
            [
                'title' => 'test_filter_title'
            ],
            [
                'tags' => 'title'
            ],
        ]);

        $response = $this->getJson(route('links.index', [
                'title' => 'filter_title'
            ])
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['success' => true])
            ->assertJsonFragment(['count' => 1])
            ->assertJsonStructure([
                'links' => [
                    '*' => [
                        'id',
                        'long_url',
                        'short_url',
                        'title',
                        'tags',
                    ]
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_get_links_with_sort(): void
    {
        ShortLink::factory()->createMany([
            [
                'title' => 'A'
            ],
            [
                'title' => 'U'
            ],
        ]);

        $response = $this->getJson(route('links.index', [
                'order_by' => 'title',
                'order_dir' => 'desc'
            ])
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['success' => true])
            ->assertJsonFragment(['count' => 2])
            ->assertJsonStructure([
                'links' => [
                    '*' => [
                        'id',
                        'long_url',
                        'short_url',
                        'title',
                        'tags',
                    ]
                ]
            ])
            ->assertJsonPath('links.0.title', 'U')
            ->assertJsonPath('links.1.title', 'A');
    }
}
