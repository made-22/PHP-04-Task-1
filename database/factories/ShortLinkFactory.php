<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class ShortLinkFactory extends Factory
{
    /**
     * @return array
     */
    public function definition(): array
    {
        $tags = [
            'Tag1',
            'Tag2',
            Str::random(10)
        ];

        return [
            'long_url' => $this->faker->url(),
            'id' => substr(md5(Str::uuid()), 0, 7),
            'title' => Str::random(10),
            'tags' => Arr::random($tags, 2)
        ];
    }
}
