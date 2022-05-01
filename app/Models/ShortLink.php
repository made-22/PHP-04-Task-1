<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    /** @var string[] */
    protected $fillable = [
        'id',
        'long_url',
        'title',
        'tags',
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    protected $casts = [
        'tags' => 'array'
    ];

    /**
     * @return string
     */
    public function getShortUrlAttribute(): string
    {
        return sprintf('%s/%s', config('app.url'), $this->id);
    }
}
