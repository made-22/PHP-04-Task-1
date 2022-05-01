<?php

namespace Tests\Feature\Api\V1\ShortLink;

use App\Models\ShortLink;
use App\Services\ShortLink\ShortLinkGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShortLinkDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_delete_not_exist_link(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        $response = $this->deleteJson(route('links.destroy', ['id' => $shortLinkId]));

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
    public function test_delete(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        ShortLink::factory()->createOne(['id' => $shortLinkId]);
        ShortLink::factory(10)->create();

        $response = $this->deleteJson(route('links.destroy', ['id' => $shortLinkId]));

        $this->assertDatabaseCount('short_links', 10);
        $this->assertDatabaseMissing('short_links', ['id' => $shortLinkId]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
