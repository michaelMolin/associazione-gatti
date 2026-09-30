<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'slug'])]
class Tag extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Tag $tag): void {
            $tag->slug = $tag->slug ?: Str::slug($tag->name);
        });
    }

    /** @return BelongsToMany<Cat, $this> */
    public function cats(): BelongsToMany
    {
        return $this->belongsToMany(Cat::class);
    }
}
