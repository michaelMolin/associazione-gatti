<?php

namespace App\Models;

use App\Enums\CatSex;
use App\Enums\CatStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $birth_date
 * @property CatSex $sex
 * @property bool $sterilized
 * @property string|null $character
 * @property string|null $image
 * @property list<string>|null $gallery
 * @property CatStatus $status
 * @property-read string|null $age
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */

#[Fillable([
    'name',
    'birth_date',
    'sex',
    'sterilized',
    'character',
    'image',
    'gallery',
    'status',
])]

class Cat extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'sterilized' => 'boolean',
            'gallery' => 'array',
            'sex' => CatSex::class,
            'status' => CatStatus::class,
        ];
    }

    /** @return BelongsToMany<Tag, $this> */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /** @return HasMany<Appointment, $this> */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Età leggibile: in mesi sotto l'anno, in anni sopra.
     *
     * @return Attribute<string|null, never>
     */
    protected function age(): Attribute
    {
        return Attribute::get(function (): ?string {
            if ($this->birth_date === null) {
                return null;
            }

            $months = (int) $this->birth_date->diffInMonths(now());

            $label = match (true) {
                $months < 1 => 'meno di un mese',
                $months < 12 => $months === 1 ? '1 mese' : "{$months} mesi",
                default => ($years = intdiv($months, 12)) === 1 ? '1 anno' : "{$years} anni",
            };

            return $this->birth_date_estimated && $months >= 1 ? "circa {$label}" : $label;
        });
    }

    /** @param Builder<Cat> $query */
    #[Scope]
    protected function available(Builder $query): void
    {
        $query->where('status', CatStatus::Available);
    }

    /**
     * Aggiunge has_interest: true se il gatto ha appuntamenti futuri.
     *
     * @param Builder<Cat> $query
     */
    #[Scope]
    protected function withInterest(Builder $query): void
    {
        $query->withExists([
            'appointments as has_interest' => fn (Builder $q) => $q->where('starts_at', '>', now()),
        ]);
    }
}
