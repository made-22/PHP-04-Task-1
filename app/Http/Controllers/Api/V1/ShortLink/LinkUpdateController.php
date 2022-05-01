<?php

namespace App\Http\Controllers\Api\V1\ShortLink;

use App\Http\Controllers\Api\V1\ApiV1BaseController;
use App\Http\Requests\Api\V1\LinkUpdateRequest;
use App\Services\ShortLink\ShortLinkService;
use Illuminate\Http\JsonResponse;

class LinkUpdateController extends ApiV1BaseController
{
    /**
     * @param string $id
     * @param LinkUpdateRequest $request
     * @param ShortLinkService $shortLinkService
     * @return JsonResponse
     */
    public function __invoke(string $id, LinkUpdateRequest $request, ShortLinkService $shortLinkService): JsonResponse
    {
        return response()->json([
            'success' => $shortLinkService->update($id, $request->all())
        ]);
    }
}
