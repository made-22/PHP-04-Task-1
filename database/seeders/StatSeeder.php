<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        Stat::factory(30)->create();
    }
}
