<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StatCollection extends ResourceCollection
{
    /** @var string */
    public static $wrap = '';

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'stats' => $this->collection,
        ];
    }
}
