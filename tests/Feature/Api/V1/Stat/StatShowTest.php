<?php

namespace Tests\Feature\Api\V1\Stat;

use App\Models\ShortLink;
use App\Models\Stat;
use App\Services\ShortLink\ShortLinkGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class StatShowTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_stat_show_with_not_exist_link()
    {
        $response = $this->getJson(route('stats.show', ['id' => 'not_exist_link']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'stats' => []
            ]);
    }

    public function test_stat_show()
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();
        $ip1 = $this->faker->unique()->ipv4();
        $ip2 = $this->faker->unique()->ipv4();

        ShortLink::factory()->createOne([
            'id' => $shortLinkId
        ]);

        Stat::factory()->createMany([
            [
                'short_link_id' => $shortLinkId,
                'ip' => $ip1,
                'created_at' => '2022-01-01 10:00:00'
            ],
            [
                'short_link_id' => $shortLinkId,
                'ip' => $ip1,
                'created_at' => '2022-01-02 10:00:00'
            ],
            [
                'short_link_id' => $shortLinkId,
                'ip' => $ip2,
                'created_at' => '2022-01-02 10:20:00'
            ]
        ]);

        $response = $this->getJson(route('stats.show', ['id' => $shortLinkId]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'stats' => [
                    [
                        'date' => '2022-01-02',
                        'total_views' => 2,
                        'unique_views' => 2
                    ],
                    [
                        'date' => '2022-01-01',
                        'total_views' => 1,
                        'unique_views' => 1
                    ]
                ]
            ]);
    }
}
