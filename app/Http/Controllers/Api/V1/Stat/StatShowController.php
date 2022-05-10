<?php

namespace App\Http\Controllers\Api\V1\Stat;

use App\Http\Controllers\Api\V1\ApiV1BaseController;
use App\Http\Resources\Api\V1\StatCollection;
use App\Services\Stat\StatService;

class StatShowController extends ApiV1BaseController
{
    /**
     * @param string $id
     * @param StatService $statService
     * @return StatCollection
     */
    public function __invoke(string $id, StatService $statService): StatCollection
    {
        return new StatCollection($statService->getByLink($id));
    }
}
