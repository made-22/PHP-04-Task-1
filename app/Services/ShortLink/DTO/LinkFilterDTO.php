<?php

namespace App\Services\ShortLink\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class LinkFilterDTO extends DataTransferObject
{
    public ?int $limit;
    public ?string $orderBy;
    public ?string $orderDir;
    public ?int $offset;
    public ?string $title;
    public ?string $tag;
}
