<?php

namespace Database\Factories;

use App\Enums\CatSex;
use App\Enums\CatStatus;
use App\Models\Cat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cat>
 */
class CatFactory extends Factory
{
    private const NAMES = [
        'Micio', 'Luna', 'Birba', 'Tigro', 'Nuvola', 'Pallino', 'Minou', 'Briciola', 'Oliver', 'Stella',
        'Romeo', 'Kira', 'Pepe', 'Mia', 'Zorro', 'Nina', 'Leo', 'Perla', 'Fuffy', 'Ciccio',
        'Gigio', 'Neve', 'Sale', 'Fumo', 'Cannella', 'Ombra', 'Tobia', 'Gea', 'Merlino', 'Polvere',
    ];

    private const CHARACTERS = [
        'Socievole e curioso, ama le coccole ma con i suoi tempi.',
        'Timido all\'inizio, poi diventa un compagno affettuosissimo.',
        'Giocherellone instancabile, adatto a una famiglia attiva.',
        'Tranquillo e riservato, cerca una casa silenziosa.',
        'Indipendente ma presente, ti segue per casa senza farsi notare.',
        'Coccolone dichiarato: se ti siedi, lui è già in braccio.',
    ];

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(self::NAMES),
            'birth_date' => fake()->dateTimeBetween('-15 years', '-2 months'),
            'sex' => fake()->randomElement([CatSex::Male, CatSex::Female]),
            'sterilized' => fake()->boolean(70),
            'character' => fake()->randomElement(self::CHARACTERS),
            'image' => null,
            'gallery' => null,
            'status' => fake()->randomElement([
                CatStatus::Available,
                CatStatus::NotAdoptable,
                CatStatus::Adopted,
            ]),
        ];
    }

    public function available(): static
    {
        return $this->state(['status' => CatStatus::Available]);
    }

    public function kitten(): static
    {
        return $this->state(['birth_date' => fake()->dateTimeBetween('-11 months', '-1 month')]);
    }
}
