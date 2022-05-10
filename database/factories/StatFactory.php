<?php

namespace Database\Factories;

use App\Models\ShortLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class StatFactory extends Factory
{
    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'short_link_id' => ShortLink::inRandomOrder()->first(),
            'ip' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
        ];
    }
}
