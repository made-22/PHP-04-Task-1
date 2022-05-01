<?php

namespace App\Services\Stat\Interfaces;

use App\Services\Stat\DTO\StatAddDTO;
use Illuminate\Database\Eloquent\Collection;

interface StatRepositoryInterface
{
    /**
     * @param StatAddDTO $statData
     * @return void
     */
    public function storeToDB(StatAddDTO $statData): void;

    /**
     * @return Collection
     */
    public function getStat(): Collection;

    /**
     * @param string $id
     * @return Collection
     */
    public function getStatByLinkId(string $id): Collection;
}
