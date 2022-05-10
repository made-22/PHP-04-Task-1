<?php

namespace Tests\Unit;

use App\Models\ShortLink;
use App\Services\ShortLink\DTO\LinkCreateDTO;
use App\Services\ShortLink\DTO\LinkFilterDTO;
use App\Services\ShortLink\ShortLinkGeneratorService;
use App\Services\ShortLink\ShortLinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use ReflectionException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\TestCase;

class ShortLinkServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     * @throws UnknownProperties
     */
    public function test_get_generated_links(): void
    {
        $linkDataFirst = [
            'id' => resolve(ShortLinkGeneratorService::class)->generate(),
            'long_url' => 'https://google.com/',
            'title' => 'Google',
            'tags' => [
                'tag1',
                'tag2'
            ]
        ];

        $linkDataSecond = [
            'id' => resolve(ShortLinkGeneratorService::class)->generate(),
            'long_url' => 'https://yandex.ru/',
        ];

        $storeLinkData = [
            new LinkCreateDTO([
                'longUrl' => $linkDataFirst['long_url'],
                'shortUrl' => $linkDataFirst['id'],
                'title' => $linkDataFirst['title'],
                'tags' => $linkDataFirst['tags']
            ]),
            new LinkCreateDTO([
                'longUrl' => $linkDataSecond['long_url'],
                'shortUrl' => $linkDataSecond['id'],
            ])
        ];

        resolve(ShortLinkService::class)->makeLinks($storeLinkData);

        $shortLinkFirst = ShortLink::findOrFail($linkDataFirst['id']);
        $shortLinkSecond = ShortLink::findOrFail($linkDataSecond['id']);

        $this->assertDatabaseCount('short_links', 2);

        $this->assertEquals($linkDataFirst['long_url'], $shortLinkFirst->long_url);
        $this->assertEquals($linkDataFirst['title'], $shortLinkFirst->title);
        $this->assertEquals($linkDataFirst['tags'], $shortLinkFirst->tags);

        $this->assertEquals($linkDataSecond['long_url'], $shortLinkSecond->long_url);
        $this->assertEquals(null, $shortLinkSecond->title);
        $this->assertEquals(null, $shortLinkSecond->tags);
    }

    /**
     * @return void
     */
    public function test_get_link(): void
    {
        $data = [
            'id' => resolve(ShortLinkGeneratorService::class)->generate(),
            'long_url' => 'https://laravel.com/',
            'title' => null,
            'tags' => ['tag1', 'tag2']
        ];

        ShortLink::factory()->createOne($data);

        $shortLinkData = resolve(ShortLinkService::class)->show($data['id']);

        $this->assertEquals($data['id'], $shortLinkData->id);
        $this->assertEquals($data['long_url'], $shortLinkData->long_url);
        $this->assertEquals($data['title'], $shortLinkData->title);
        $this->assertEquals($data['tags'], $shortLinkData->tags);
    }

    /**
     * @throws UnknownProperties
     */
    public function test_get_links(): void
    {
        $testTitle = 'Test';

        ShortLink::factory(10)->create();
        ShortLink::factory()->createOne([
            'title' => $testTitle
        ]);

        $filterDTO = new LinkFilterDTO([
            'title' => 'est'
        ]);

        $shortLinkFiltered = resolve(ShortLinkService::class)->getList($filterDTO);
        $shortLink = resolve(ShortLinkService::class)->getList(new LinkFilterDTO());

        $this->assertEquals(1, $shortLinkFiltered->count());
        $this->assertEquals($testTitle, $shortLinkFiltered->first()->title);

        $this->assertEquals(11, $shortLink->count());

    }

    /**
     * @return void
     */
    public function test_delete_link(): void
    {
        $shortLinkId = resolve(ShortLinkGeneratorService::class)->generate();

        ShortLink::factory()->createOne([
            'id' => $shortLinkId
        ]);
        ShortLink::factory(10)->create();

        $result = resolve(ShortLinkService::class)->delete($shortLinkId);

        $this->assertDatabaseMissing('short_links', ['id' => $shortLinkId]);
        $this->assertDatabaseCount('short_links', 10);
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function test_update_link(): void
    {
        $data = [
            'id' => resolve(ShortLinkGeneratorService::class)->generate(),
            'long_url' => 'https://laravel.com/',
            'title' => null,
            'tags' => ['tag1', 'tag2']
        ];

        $updateData = [
            'title' => 'Test',
            'long_url' => 'https://laravel2.com/',
            'tags' => ['test1', 'test2']
        ];

        ShortLink::factory()->createOne($data);

        $result = resolve(ShortLinkService::class)->update($data['id'], $updateData);
        $shortLink = ShortLink::findOrFail($data['id']);

        $this->assertTrue($result);
        $this->assertEquals($updateData['title'], $shortLink->title);
        $this->assertEquals($updateData['long_url'], $shortLink->long_url);
        $this->assertEquals($updateData['tags'], $shortLink->tags);
    }

    /**
     * @throws UnknownProperties
     */
    public function test_store_links()
    {
        $linkDataFirst = [
            'id' => resolve(ShortLinkGeneratorService::class)->generate(),
            'long_url' => 'https://google.com/',
            'title' => 'Google',
            'tags' => [
                'tag1',
                'tag2'
            ]
        ];

        $linkDataSecond = [
            'id' => resolve(ShortLinkGeneratorService::class)->generate(),
            'long_url' => 'https://yandex.ru/',
        ];

        $storeLinkData = [
            new LinkCreateDTO([
                'longUrl' => $linkDataFirst['long_url'],
                'shortUrl' => $linkDataFirst['id'],
                'title' => $linkDataFirst['title'],
                'tags' => $linkDataFirst['tags']
            ]),
            new LinkCreateDTO([
                'longUrl' => $linkDataSecond['long_url'],
                'shortUrl' => $linkDataSecond['id'],
            ])
        ];

        try {
            $obj = resolve(ShortLinkService::class);
            $reflectionCls = new ReflectionClass($obj);
            $method = $reflectionCls->getMethod('storeLinks');
            $method->setAccessible(true);
            $method->invokeArgs($obj, [
                'data' => $storeLinkData
            ]);

        } catch (ReflectionException) {
            $this->fail();
        }

        $shortLinkFirst = ShortLink::findOrFail($linkDataFirst['id']);
        $shortLinkSecond = ShortLink::findOrFail($linkDataSecond['id']);

        $this->assertDatabaseCount('short_links', 2);

        $this->assertEquals($linkDataFirst['long_url'], $shortLinkFirst->long_url);
        $this->assertEquals($linkDataFirst['title'], $shortLinkFirst->title);
        $this->assertEquals($linkDataFirst['tags'], $shortLinkFirst->tags);

        $this->assertEquals($linkDataSecond['long_url'], $shortLinkSecond->long_url);
        $this->assertEquals(null, $shortLinkSecond->title);
        $this->assertEquals(null, $shortLinkSecond->tags);
    }
}
