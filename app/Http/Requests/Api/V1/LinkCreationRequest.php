<?php

namespace App\Http\Requests\Api\V1;

use App\Rules\CorrectUrlRule;
use App\Services\ShortLink\DTO\LinkCreateDTO;
use App\Services\ShortLink\ShortLinkGeneratorService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LinkCreationRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.long_url' => [
                'required',
                'url',
                new CorrectUrlRule
            ],
            '*.title' => [
                'string',
                'min:2',
                'max:255'
            ],
            '*.tags' => [
                'array'
            ],
            '*.tags.*' => [
                'string',
                'min:1'
            ]
        ];
    }

    /**
     * @return array
     * @throws UnknownProperties
     * @throws BindingResolutionException
     */
    public function data(): array
    {
        $data = [];
        $generatorService = app()->make(ShortLinkGeneratorService::class);

        foreach ($this->json->all() as $item) {
            $data[] = new LinkCreateDTO([
                'shortUrl' => $generatorService->generate(),
                'longUrl' => $item['long_url'],
                'title' => $item['title'] ?? null,
                'tags' => $item['tags'] ?? null
            ]);
        }

        return $data;
    }
}
