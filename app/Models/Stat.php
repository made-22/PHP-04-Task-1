<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;

    /** @var string[] */
    protected $fillable = [
        'short_link_id',
        'ip',
        'user_agent',
        'created_at',
        'updated_at',
    ];
}
