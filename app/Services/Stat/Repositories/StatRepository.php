<?php

namespace App\Services\Stat\Repositories;

use App\Models\Stat;
use App\Services\Stat\DTO\StatAddDTO;
use App\Services\Stat\Interfaces\StatRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class StatRepository implements StatRepositoryInterface
{
    /**
     * @param StatAddDTO $statData
     * @return void
     */
    public function storeToDB(StatAddDTO $statData): void
    {
        Stat::query()
            ->create([
                'short_link_id' => $statData->shortLinkId,
                'ip' => $statData->ip,
                'user_agent' => $statData->userAgent
            ]);
    }

    /**
     * @return Collection
     */
    public function getStat(): Collection
    {
        return Stat::query()
            ->select([
                'short_link_id',
                DB::raw('COUNT(*) AS total_views'),
                DB::raw('COUNT(DISTINCT ip) AS unique_views')
            ])
            ->orderBy('unique_views', 'desc')
            ->groupBy('short_link_id')
            ->get();
    }

    /**
     * @param string $id
     * @return Collection
     */
    public function getStatByLinkId(string $id): Collection
    {
        return Stat::query()
            ->select([
                DB::raw('DATE(created_at) AS date'),
                DB::raw('COUNT(*) AS total_views'),
                DB::raw('COUNT(DISTINCT ip) AS unique_views'),
            ])
            ->where('short_link_id', $id)
            ->orderBy('date', 'desc')
            ->groupBy('date')
            ->get();
    }
}
