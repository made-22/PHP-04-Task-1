<?php

namespace App\Services\ShortLink;

use Illuminate\Support\Str;

class ShortLinkGeneratorService
{
    /** @var int */
    public const SHORT_LINK_LENGTH = 7;

    /**
     * @return string
     */
    public function generate(): string
    {
        return substr(
            md5(
                (string)Str::uuid()
            ),
            0, self::SHORT_LINK_LENGTH
        );
    }
}
