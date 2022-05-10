<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShortLinkBaseDataCollection extends ResourceCollection
{
    /** @var string */
    public static $wrap = '';

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'success' => true,
            'links' => $this->collection,
            'count' => $this->count()
        ];
    }
}
