<?php

namespace App\Services\Stat\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class StatAddDTO extends DataTransferObject
{
    public string $shortLinkId;
    public ?string $ip;
    public ?string $userAgent;
}
