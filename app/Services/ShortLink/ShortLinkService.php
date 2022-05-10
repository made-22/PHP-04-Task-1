<?php

namespace App\Services\ShortLink;

use App\Exceptions\ShortLink\CantStoreShortLinksException;
use App\Models\ShortLink;
use App\Services\ShortLink\DTO\LinkCreateDTO;
use App\Services\ShortLink\DTO\LinkFilterDTO;
use App\Services\ShortLink\Interfaces\ShortLinkRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ShortLinkService
{
    /** @var ShortLinkRepositoryInterface */
    private ShortLinkRepositoryInterface $repository;

    /**
     * @param ShortLinkRepositoryInterface $repository
     */
    public function __construct(ShortLinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param LinkCreateDTO[] $data
     */
    public function makeLinks(array $data): array
    {
        return $this->storeLinks($data);
    }

    /**
     * @param string $id
     * @return ShortLink
     */
    public function show(string $id): ShortLink
    {
        return $this->repository->getById($id);
    }

    /**
     * @param string[] $ids
     * @return Collection
     */
    public function getListByIds(array $ids): Collection
    {
        return $this->repository->getByIds($ids);
    }

    /**
     * @param LinkFilterDTO $filterData
     * @return Collection
     */
    public function getList(LinkFilterDTO $filterData): Collection
    {
        return $this->repository->getLinks($filterData);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return $this->repository->update($id, $updateData);
    }

    /**
     * @param LinkCreateDTO[] $data
     */
    private function storeLinks(array $data): ?array
    {
        return $this->repository->storeLinks($data);
    }
}
