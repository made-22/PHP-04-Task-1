<?php

namespace App\Services\Stat;

use App\Jobs\AddLinkStatJob;
use App\Services\Stat\DTO\StatAddDTO;
use App\Services\Stat\Interfaces\StatRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StatService
{
    /** @var string */
    public const STAT_QUEUE_NAME = 'links_stat';

    /** @var StatRepositoryInterface */
    private StatRepositoryInterface $repository;

    /**
     * @param StatRepositoryInterface $repository
     */
    public function __construct(StatRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param StatAddDTO $statData
     * @return void
     */
    public function add(StatAddDTO $statData): void
    {
        AddLinkStatJob::dispatch($statData)->onQueue(self::STAT_QUEUE_NAME);
    }

    /**
     * @param StatAddDTO $statData
     * @return void
     */
    public function storeToDB(StatAddDTO $statData): void
    {
        $this->repository->storeToDB($statData);
    }

    /**
     * @return Collection
     */
    public function getStat(): Collection
    {
        return $this->repository->getStat();
    }

    /**
     * @param string $id
     * @return Collection
     */
    public function getStatByLinkId(string $id): Collection
    {
        return $this->repository->getStatByLinkId($id);
    }
}
