<?php

namespace Tests\Unit;

use App\Services\ShortLink\ShortLinkGeneratorService;
use PHPUnit\Framework\TestCase;

class ShortLinkGeneratorServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function test_generate(): void
    {
        $shortLink = resolve(ShortLinkGeneratorService::class)->generate();

        $this->assertTrue(is_string($shortLink));

        $this->assertTrue(strlen($shortLink) === ShortLinkGeneratorService::SHORT_LINK_LENGTH);
    }
}
