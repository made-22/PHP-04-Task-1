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
    public function store(StatAddDTO $statData): void;

    /**
     * @return Collection
     */
    public function getList(): Collection;

    /**
     * @param string $id
     * @return Collection
     */
    public function getByLink(string $id): Collection;
}
