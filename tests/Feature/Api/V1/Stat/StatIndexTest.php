<?php

namespace Tests\Feature\Api\V1\Stat;

use App\Models\ShortLink;
use App\Models\Stat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class StatIndexTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_stat_index()
    {
        $shortLinkId = '7a3b4cv';

        ShortLink::factory()->createOne([
            'id' => $shortLinkId
        ]);

        Stat::factory(2)->create([
            'short_link_id' => $shortLinkId,
            'ip' => $this->faker->ipv4()
        ]);

        $response = $this->getJson(route('stats.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'stats' => [
                    [
                        'short_link_id' => $shortLinkId,
                        'total_views' => 2,
                        'unique_views' => 1
                    ]
                ]
            ]);
    }
}
