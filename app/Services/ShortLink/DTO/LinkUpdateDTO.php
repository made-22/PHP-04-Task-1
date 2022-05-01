<?php

namespace App\Services\ShortLink\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class LinkUpdateDTO extends DataTransferObject
{
    public string $id;
    public ?string $longUrl;
    public ?string $title;
    public ?array $tags;
}
