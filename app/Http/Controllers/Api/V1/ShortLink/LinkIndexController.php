<?php

namespace App\Http\Controllers\Api\V1\ShortLink;

use App\Http\Controllers\Api\V1\ApiV1BaseController;
use App\Http\Requests\Api\V1\LinkIndexRequest;
use App\Http\Resources\Api\V1\ShortLinkCollection;
use App\Services\ShortLink\ShortLinkService;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LinkIndexController extends ApiV1BaseController
{
    /**
     * @param LinkIndexRequest $request
     * @param ShortLinkService $shortLinkService
     * @return ShortLinkCollection
     * @throws UnknownProperties
     */
    public function __invoke(LinkIndexRequest $request, ShortLinkService $shortLinkService): ShortLinkCollection
    {
        return new ShortLinkCollection(
            $shortLinkService->getLinks(
                $request->data()
            )
        );
    }
}
