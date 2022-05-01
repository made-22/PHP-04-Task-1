<?php

namespace App\Http\Requests\Site;

use App\Services\Stat\DTO\StatAddDTO;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LinkRedirectRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @throws UnknownProperties
     */
    public function data(): StatAddDTO
    {
        return new StatAddDTO([
            'shortLinkId' => $this->route('id'),
            'ip' => $this->ip(),
            'userAgent' => $this->userAgent(),
        ]);
    }
}
