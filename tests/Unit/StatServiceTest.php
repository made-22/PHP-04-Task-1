<?php

namespace Tests\Unit;

use App\Jobs\AddLinkStatJob;
use App\Models\ShortLink;
use App\Models\Stat;
use App\Services\ShortLink\ShortLinkGeneratorService;
use App\Services\Stat\DTO\StatAddDTO;
use App\Services\Stat\StatService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\TestCase;

class StatServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     * @throws UnknownProperties
     */
    public function test_add_link_stat_job(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        ShortLink::factory()->createOne([
            'id' => $shortLinkId
        ]);

        $statData = [
            'short_link_id' => $shortLinkId,
            'ip' => '172.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_1 rv:5.0) Gecko/20121028 Firefox/36.0'
        ];

        $statAddDTO = new StatAddDTO([
            'shortLinkId' => $statData['short_link_id'],
            'ip' => $statData['ip'],
            'userAgent' => $statData['user_agent']
        ]);

        $listener = new AddLinkStatJob($statAddDTO);
        $listener->handle(resolve(StatService::class));

        $this->assertDatabaseCount('stats', 1);
        $this->assertDatabaseHas('stats', $statData);
    }

    /**
     * @return void
     * @throws UnknownProperties
     */
    public function test_add(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        ShortLink::factory()->createOne([
            'id' => $shortLinkId
        ]);

        $statData = [
            'short_link_id' => $shortLinkId,
            'ip' => '172.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_1 rv:5.0) Gecko/20121028 Firefox/36.0'
        ];

        $statAddDTO = new StatAddDTO([
            'shortLinkId' => $statData['short_link_id'],
            'ip' => $statData['ip'],
            'userAgent' => $statData['user_agent']
        ]);

        resolve(StatService::class)->add($statAddDTO);

        $this->assertDatabaseCount('stats', 1);
        $this->assertDatabaseHas('stats', $statData);
    }

    /**
     * @return void
     * @throws UnknownProperties
     */
    public function test_add_by_job_with_not_exist_short_link(): void
    {
        $data = new StatAddDTO([
            'shortLinkId' => 'not_exist_id',
            'ip' => '172.0.0.1',
            'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_1 rv:5.0) Gecko/20121028 Firefox/36.0'
        ]);

        try {
            resolve(StatService::class)->add($data);

            $this->fail();
        } catch (QueryException) {
            $this->assertTrue(true);
        }
    }

    /**
     * @return void
     * @throws UnknownProperties
     */
    public function test_store_to_db(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        ShortLink::factory()->createOne([
            'id' => $shortLinkId
        ]);

        $statData = [
            'short_link_id' => $shortLinkId,
            'ip' => '172.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_1 rv:5.0) Gecko/20121028 Firefox/36.0'
        ];

        $statAddDTO = new StatAddDTO([
            'shortLinkId' => $statData['short_link_id'],
            'ip' => $statData['ip'],
            'userAgent' => $statData['user_agent']
        ]);

        resolve(StatService::class)->storeToDB($statAddDTO);

        $this->assertDatabaseCount('stats', 1);
        $this->assertDatabaseHas('stats', $statData);
    }

    /**
     * @return void
     */
    public function test_get_stat(): void
    {
        $shortLinkId1 = resolve(ShortLinkGeneratorService::class)->generate();
        $shortLinkId2 = resolve(ShortLinkGeneratorService::class)->generate();

        ShortLink::factory()->createMany([
            [
                'id' => $shortLinkId1
            ],
            [
                'id' => $shortLinkId2
            ],
        ]);

        Stat::factory(2)->createMany([
            [
                'short_link_id' => $shortLinkId1,
                'ip' => '192.0.0.7'
            ],
            [
                'short_link_id' => $shortLinkId2,
                'ip' => '192.0.0.8'
            ],
        ]);

        Stat::factory(2)->createMany([
            [
                'short_link_id' => $shortLinkId1,
                'ip' => '192.0.0.1'
            ],
            [
                'short_link_id' => $shortLinkId2,
                'ip' => '192.0.0.2'
            ],
        ]);

        $statData = resolve(StatService::class)->getStat();
        $shortLinkFirst = $statData->where('short_link_id', $shortLinkId1);
        $shortLinkSecond = $statData->where('short_link_id', $shortLinkId2);

        $this->assertTrue($shortLinkFirst->count() === 1);
        $this->assertTrue($shortLinkSecond->count() === 1);

        $this->assertEquals(4, $shortLinkFirst->first()->total_views);
        $this->assertEquals(2, $shortLinkFirst->first()->unique_views);

        $this->assertEquals(4, $shortLinkSecond->first()->total_views);
        $this->assertEquals(2, $shortLinkSecond->first()->unique_views);
    }

    /**
     * @return void
     */
    public function test_get_stat_by_link_id(): void
    {
        $shortLinkId1 = resolve(ShortLinkGeneratorService::class)->generate();
        $shortLinkId2 = resolve(ShortLinkGeneratorService::class)->generate();

        ShortLink::factory()->createMany([
            [
                'id' => $shortLinkId1
            ],
            [
                'id' => $shortLinkId2
            ],
        ]);

        Stat::factory(2)->createMany([
            [
                'short_link_id' => $shortLinkId1,
                'ip' => '192.0.0.7',
                'created_at' => '2022-01-01 10:00:00'
            ],
            [
                'short_link_id' => $shortLinkId1,
                'ip' => '192.0.0.8',
                'created_at' => '2022-01-01 10:00:00'
            ],
        ]);

        Stat::factory(3)->createMany([
            [
                'short_link_id' => $shortLinkId1,
                'ip' => '192.0.0.1',
                'created_at' => '2022-01-02 10:00:00'
            ],
            [
                'short_link_id' => $shortLinkId2,
                'ip' => '192.0.0.2',
                'created_at' => '2022-01-02 10:00:00'
            ],
        ]);

        $statDataLinkIdFirst = resolve(StatService::class)->getStatByLinkId($shortLinkId1);
        $statDataLinkIdSecond = resolve(StatService::class)->getStatByLinkId($shortLinkId2);

        $statDataLinkIdByDateFirst = $statDataLinkIdFirst->where('date', '2022-01-01')->first();
        $statDataLinkIdByDateSecond = $statDataLinkIdSecond->where('date', '2022-01-02')->first();

        // group by date test
        $this->assertEquals(2, $statDataLinkIdFirst->count());
        $this->assertEquals(1, $statDataLinkIdSecond->count());

        $this->assertEquals(4, $statDataLinkIdByDateFirst->total_views);
        $this->assertEquals(2, $statDataLinkIdByDateFirst->unique_views);

        $this->assertEquals(3, $statDataLinkIdByDateSecond->total_views);
        $this->assertEquals(1, $statDataLinkIdByDateSecond->unique_views);
    }
}
