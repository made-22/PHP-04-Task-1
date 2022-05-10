<?php

namespace App\Http\Resources\Api\V1;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortLinkBaseDataResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var ShortLink $this */

        return [
            'id' => $this->id,
            'long_url' => $this->long_url,
            'short_url' => $this->short_url
        ];
    }

    /**
     * @param $request
     * @return bool[]
     */
    public function with($request): array
    {
        return [
            'success' => true
        ];
    }
}
