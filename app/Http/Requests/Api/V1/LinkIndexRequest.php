<?php

namespace App\Http\Requests\Api\V1;

use App\Services\ShortLink\DTO\LinkFilterDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LinkIndexRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'limit' => [
                'integer',
                'min:1'
            ],
            'offset' => [
                'integer',
                'min:0'
            ],
            'order_by' => [
                'string',
                'min:1'
            ],
            'order_dir' => [
                'string',
                Rule::in(['asc', 'desc'])
            ],
            'title' => [
                'string',
                'min:2',
                'max:255'
            ],
            'tag' => [
                'string',
                'min:2',
                'max:255'
            ]
        ];
    }

    /**
     * @throws UnknownProperties
     */
    public function data(): LinkFilterDTO
    {
        return new LinkFilterDTO([
            'limit' => $this->get('limit'),
            'offset' => $this->get('offset'),
            'orderBy' => $this->get('order_by'),
            'orderDir' => $this->get('order_dir'),
            'title' => $this->get('title'),
            'tag' => $this->get('tag'),
        ]);
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'order_by' => $this->get('order_by') ?: 'created_at',
            'order_dir' => $this->get('order_dir') ?: 'desc',
        ]);
    }
}
