<?php

namespace Tests\Feature\Site\ShortLink;

use App\Models\ShortLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ShortLinkRedirectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_redirect_with_not_exist_link(): void
    {
        $response = $this->get(route('link.redirect', ['id' => 'not_exist_id']));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function test_redirect(): void
    {
        $statLink = ShortLink::factory()->createOne();

        $response = $this->get(route('link.redirect', ['id' => $statLink->id]));

        $response->assertStatus(Response::HTTP_MOVED_PERMANENTLY);
        $response->assertRedirect($statLink->long_url);
    }
}
