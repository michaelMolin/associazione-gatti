<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Cat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cat_id' => Cat::factory(),
            'starts_at' => self::slot(fake()->unique()->numberBetween(0, 8 * 60)),
            'accepted_at' => null,
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn () => ['accepted_at' => now()]);
    }

    /**
     * N-esimo slot futuro da un'ora, lun-sab 9-17, domenica esclusa.
     * Provvisorio finché gli orari non stanno nel file di config.
     */
    private static function slot(int $index): Carbon
    {
        $day = today()->addDay();
        $slotsPerDay = 8;

        for ($i = intdiv($index, $slotsPerDay); $i > 0 || $day->isSunday(); ) {
            $day->addDay();
            if (! $day->isSunday()) {
                $i--;
            }
        }

        return $day->setTime(9 + $index % $slotsPerDay, 0);
    }
}
