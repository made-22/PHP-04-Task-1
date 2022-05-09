<?php

namespace App\Http\Controllers\Api\V1\ShortLink;

use App\Http\Controllers\Api\V1\ApiV1BaseController;
use App\Http\Requests\Api\V1\LinkCreationRequest;
use App\Http\Resources\Api\V1\ShortLinkBaseDataCollection;
use App\Services\ShortLink\ShortLinkGeneratorService;
use App\Services\ShortLink\ShortLinkService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LinkCreationController extends ApiV1BaseController
{
    /**
     * @param LinkCreationRequest $request
     * @param ShortLinkGeneratorService $shortLinkGeneratorService
     * @param ShortLinkService $shortLinkService
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws UnknownProperties
     */
    public function __invoke(
        LinkCreationRequest $request,
        ShortLinkGeneratorService $shortLinkGeneratorService,
        ShortLinkService $shortLinkService
    ): JsonResponse {
        $linkIds = $shortLinkService->makeLinks(
            $request->data()
        );

        if (empty($linkIds)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, __('exceptions.cant_store_links'));
        }

        $createdResourceCollection = new ShortLinkBaseDataCollection(
            $shortLinkService->getLinksWithBaseDataByIds($linkIds)
        );

        return $createdResourceCollection
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }
}
