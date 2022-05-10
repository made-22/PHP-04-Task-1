<?php

namespace App\Jobs;

use App\Services\Stat\DTO\StatAddDTO;
use App\Services\Stat\StatService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddLinkStatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var StatAddDTO */
    private StatAddDTO $statData;

    public function __construct(StatAddDTO $statData)
    {
        $this->statData = $statData;
    }

    /**
     * @param StatService $statService
     * @return void
     */
    public function handle(StatService $statService): void
    {
        $statService->store($this->statData);
    }
}
