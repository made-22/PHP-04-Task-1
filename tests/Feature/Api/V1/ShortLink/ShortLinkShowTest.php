<?php

namespace Tests\Feature\Api\V1\ShortLink;

use App\Models\ShortLink;
use App\Services\ShortLink\ShortLinkGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShortLinkShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_get_not_exist_link(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        $response = $this->getJson(route('links.show', ['id' => $shortLinkId]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'success',
                'errors'
            ])
            ->assertJson(['success' => false]);
    }

    /**
     * @return void
     */
    public function test_show_link(): void
    {
        $data = [
            'id' => resolve(ShortLinkGeneratorService::class)->generate(),
            'title' => 'Test',
            'long_url' => 'https://www.google.com/',
            'tags' => [
                'tag1',
                'tag2'
            ]
        ];

        ShortLink::factory()->createOne($data);
        ShortLink::factory(10)->create();

        $response = $this->getJson(route('links.show', ['id' => $data['id']]));

        $shortLink = ShortLink::findOrFail($data['id']);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $data['id'],
                    'long_url' => $data['long_url'],
                    'short_url' => $shortLink->short_url,
                    'title' => $data['title'],
                    'tags' => $data['tags']
                ]
            ])
            ->assertJsonFragment(['success' => true]);
    }
}
