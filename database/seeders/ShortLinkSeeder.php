<?php

namespace Database\Seeders;

use App\Models\ShortLink;
use Illuminate\Database\Seeder;

class ShortLinkSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        ShortLink::factory(10)->create();
    }
}
