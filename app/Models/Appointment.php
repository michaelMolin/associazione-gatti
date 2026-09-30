<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $cat_id
 * @property Carbon $starts_at
 * @property Carbon|null $accepted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['cat_id', 'starts_at'])]
class Appointment extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Cat, $this> */
    public function cat(): BelongsTo
    {
        return $this->belongsTo(Cat::class);
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    /** @param Builder<Appointment> $query */
    #[Scope]
    protected function upcoming(Builder $query): void
    {
        $query->where('starts_at', '>', now());
    }

    /** @param Builder<Appointment> $query */
    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->whereNull('accepted_at');
    }
}
