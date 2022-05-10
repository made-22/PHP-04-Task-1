<?php

namespace App\Http\Controllers\Api\V1\ShortLink;

use App\Http\Controllers\Api\V1\ApiV1BaseController;
use App\Services\ShortLink\ShortLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LinkDestroyController extends ApiV1BaseController
{
    /**
     * @param string $id
     * @param ShortLinkService $shortLinkService
     * @return JsonResponse
     */
    public function __invoke(string $id, ShortLinkService $shortLinkService): JsonResponse
    {
        if (!$shortLinkService->delete($id)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
