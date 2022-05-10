<?php

namespace Tests\Feature\Api\V1\ShortLink;

use App\Models\ShortLink;
use App\Services\ShortLink\ShortLinkGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShortLinkPatchUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_patch_full_update(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        $updateData = [
            'id' => $shortLinkId,
            'title' => 'Test',
            'long_url' => 'https://laravel.com/',
            'tags' => ['test1', 'test2']
        ];

        ShortLink::factory()->createOne([
            'id' => $shortLinkId,
            'long_url' => 'https://google.com/'
        ]);

        $response = $this->patchJson(route('links.update', $updateData));

        $shortLink = ShortLink::findOrFail($shortLinkId);

        $this->assertEquals($updateData['id'], $shortLink->id);
        $this->assertEquals($updateData['title'], $shortLink->title);
        $this->assertEquals($updateData['long_url'], $shortLink->long_url);
        $this->assertEquals($updateData['tags'], $shortLink->tags);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['success' => true]);
    }

    /**
     * @return void
     */
    public function test_patch_one_field_update(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        $updateData = [
            'id' => $shortLinkId,
            'long_url' => 'https://laravel.com/',
        ];

        $shortLinkCreated = ShortLink::factory()->createOne([
            'id' => $shortLinkId,
            'long_url' => 'https://google.com/'
        ]);

        $response = $this->patchJson(route('links.update', $updateData));

        $shortLink = ShortLink::findOrFail($shortLinkId);

        $this->assertEquals($shortLinkCreated->id, $shortLink->id);
        $this->assertEquals($shortLinkCreated->title, $shortLink->title);
        $this->assertEquals($updateData['long_url'], $shortLink->long_url);
        $this->assertEquals($shortLinkCreated->tags, $shortLink->tags);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['success' => true]);
    }
}
