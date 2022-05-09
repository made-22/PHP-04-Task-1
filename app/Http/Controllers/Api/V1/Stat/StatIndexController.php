<?php

namespace App\Http\Controllers\Api\V1\Stat;

use App\Http\Controllers\Api\V1\ApiV1BaseController;
use App\Http\Resources\Api\V1\StatCollection;
use App\Services\Stat\StatService;

class StatIndexController extends ApiV1BaseController
{
    public function __invoke(StatService $statService): StatCollection
    {
       return new StatCollection(
           $statService->get()
       );
    }
}
