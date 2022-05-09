<?php

namespace App\Services\ShortLink\Repositories;

use App\Models\ShortLink;
use App\Services\ShortLink\DTO\LinkCreateDTO;
use App\Services\ShortLink\DTO\LinkFilterDTO;
use App\Services\ShortLink\Interfaces\ShortLinkRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

final class ShortLinkRepository implements ShortLinkRepositoryInterface
{
    /**
     * @param string $id
     * @return ShortLink
     */
    public function getById(string $id): ShortLink
    {
        return ShortLink::query()
            ->findOrFail($id, [
                'id',
                'long_url',
                'title',
                'tags',
                'created_at',
                'updated_at'
            ]);
    }

    /**
     * @param string $id
     * @return ShortLink
     */
    public function getLinkWithBaseData(string $id): ShortLink
    {
        return ShortLink::query()
            ->findOrFail($id, ['id', 'long_url']);
    }

    /**
     * @param string[] $ids
     * @return Collection
     */
    public function getLinksWithBaseDataByIds(array $ids): Collection
    {
        return ShortLink::query()
            ->select(['id', 'long_url'])
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * @param LinkFilterDTO $filterData
     * @return Collection
     */
    public function getLinks(LinkFilterDTO $filterData): Collection
    {
        $query = ShortLink::query()
            ->select([
                'id',
                'long_url',
                'title',
                'tags',
                'created_at',
                'updated_at'
            ]);

        if (!empty($filterData->tag)) {
            $query->where('tags', 'like', '%' . $filterData->tag . '%');
        }

        if (!empty($filterData->title)) {
            $query->where('title', 'like', '%' . $filterData->title . '%');
        }

        if (!empty($filterData->orderBy)) {
            $query->orderBy($filterData->orderBy, $filterData->orderDir ?: 'asc');
        }

        if (isset($filterData->offset)) {
            $query->skip($filterData->offset);
        }

        if (!empty($filterData->limit)) {
            $query->take($filterData->limit);
        }

        return $query->get();
    }

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return (bool)ShortLink::query()
            ->findOrFail($id, ['id'])
            ->delete();
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return (bool)ShortLink::query()
            ->findOrFail($id, ['id',])
            ->update($updateData);
    }

    /**
     * @param LinkCreateDTO[] $data
     */
    public function storeLinks(array $data): ?array
    {
        $storeData = [];
        $storedLinkIds = [];
        $now = Carbon::now();

        foreach ($data as $createDTO) {
            $storeData[] = [
                'id' => $createDTO->shortUrl,
                'long_url' => $createDTO->longUrl,
                'title' => $createDTO->title,
                'tags' => json_encode($createDTO->tags),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $storedLinkIds[] = $createDTO->shortUrl;
        }

        return ShortLink::query()->insert($storeData) ? $storedLinkIds : null;
    }
}
