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
     * @throws CantStoreShortLinksException
     */
    public function getGeneratedLinks(array $data): Collection
    {
        $storedLinksIds = $this->storeLinks($data);

        if (empty($storedLinksIds)) {
            throw new CantStoreShortLinksException(__('exceptions.cant_store_links'));
        }

        return $this->repository->getLinksBaseDataByIds($storedLinksIds);
    }

    /**
     * @param string $id
     * @return ShortLink
     */
    public function getLink(string $id): ShortLink
    {
        return $this->repository->getLink($id);
    }

    /**
     * @param string $id
     * @return ShortLink
     */
    public function getLinkBaseData(string $id): ShortLink
    {
        return $this->repository->getLinkBaseData($id);
    }

    /**
     * @param LinkFilterDTO $filterData
     * @return Collection
     */
    public function getLinks(LinkFilterDTO $filterData): Collection
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
