<?php

namespace App\Http\Resources\Api\V1;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortLinkResource extends JsonResource
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
            'short_url' => $this->short_url,
            'title' => $this->title,
            'tags' => $this->tags,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
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
