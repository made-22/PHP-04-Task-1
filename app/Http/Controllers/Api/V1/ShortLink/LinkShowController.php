<?php

namespace App\Http\Controllers\Api\V1\ShortLink;

use App\Http\Controllers\Api\V1\ApiV1BaseController;
use App\Http\Resources\Api\V1\ShortLinkResource;
use App\Services\ShortLink\ShortLinkService;

class LinkShowController extends ApiV1BaseController
{
    /**
     * @param string $id
     * @param ShortLinkService $shortLinkService
     * @return ShortLinkResource
     */
    public function __invoke(string $id, ShortLinkService $shortLinkService): ShortLinkResource
    {
        return new ShortLinkResource(
            $shortLinkService->show($id)
        );
    }
}
