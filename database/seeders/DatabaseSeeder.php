<?php

namespace Database\Seeders;

use App\Enums\CatStatus;
use App\Models\Appointment;
use App\Models\Cat;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private const TAGS = [
        'Socievole', 'Timido', 'Coccolone', 'Giocherellone', 'Indipendente', 'Tranquillo',
        'Ok con altri gatti', 'Ok con cani', 'Ok con bambini', 'Da appartamento', 'Esigenze speciali',
    ];

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@associazionegatti.com',
            'is_admin' => true,
        ]);

        $users = User::factory(20)->create();

        $tags = collect(self::TAGS)->map(fn (string $name) => Tag::factory()->create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]));

        $cats = Cat::factory(200)->create();
        $cats->each(fn (Cat $cat) => $cat->tags()->attach($tags->random(rand(1, 3))->pluck('id')));

        $available = $cats->where('status', CatStatus::Available);

        foreach ($users as $user) {
            $user->favoriteCats()->attach($available->random(rand(0, 5))->pluck('id'));
        }

        Appointment::factory(30)
            ->recycle($users)
            ->state(fn () => ['cat_id' => $available->random()->id])
            ->create();
    }
}
