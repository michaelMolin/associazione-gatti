<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        // Lo slug va valorizzato qui: il seeder gira senza model events,
        // quindi l'evento saving del model non scatta.
        return [
            'name' => Str::ucfirst($name),
            'slug' => Str::slug($name),
        ];
    }
}
