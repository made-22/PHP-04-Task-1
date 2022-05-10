<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\Site\LinkRedirectRequest;
use App\Services\ShortLink\ShortLinkService;
use App\Services\Stat\StatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LinkRedirectController
{
    /**
     * @throws UnknownProperties
     */
    public function __invoke(
        string $id,
        LinkRedirectRequest $request,
        StatService $statService,
        ShortLinkService $shortLinkService
    ): RedirectResponse {
        $linkData = $shortLinkService->show($id);

        $statService->add($request->data());

        return redirect($linkData->long_url, Response::HTTP_MOVED_PERMANENTLY);
    }
}
