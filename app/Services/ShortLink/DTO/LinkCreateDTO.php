<?php

namespace App\Services\ShortLink\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class LinkCreateDTO extends DataTransferObject
{
    public string $longUrl;
    public ?string $shortUrl;
    public ?string $title;
    public ?array $tags;
}
