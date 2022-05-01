<?php

namespace App\Services\ShortLink\Interfaces;

use App\Models\ShortLink;
use App\Services\ShortLink\DTO\LinkCreateDTO;
use App\Services\ShortLink\DTO\LinkFilterDTO;
use Illuminate\Database\Eloquent\Collection;

interface ShortLinkRepositoryInterface
{
    /**
     * @param string $id
     * @return ShortLink
     */
    public function getLink(string $id): ShortLink;

    /**
     * @param string $id
     * @return ShortLink
     */
    public function getLinkBaseData(string $id): ShortLink;

    /**
     * @param string[] $ids
     * @return Collection
     */
    public function getLinksBaseDataByIds(array $ids): Collection;

    /**
     * @param LinkFilterDTO $filterData
     * @return Collection
     */
    public function getLinks(LinkFilterDTO $filterData): Collection;

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool;

    /**
     * @param LinkCreateDTO[] $data
     */
    public function storeLinks(array $data): ?array;
}
