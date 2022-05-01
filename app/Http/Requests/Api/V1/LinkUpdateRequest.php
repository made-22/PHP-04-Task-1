<?php

namespace App\Http\Requests\Api\V1;

use App\Rules\CorrectUrlRule;
use Illuminate\Foundation\Http\FormRequest;

class LinkUpdateRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'long_url' => [
                'url',
                new CorrectUrlRule()
            ],
            'title' => [
                'string',
                'min:2',
                'max:255'
            ]
        ];
    }
}
