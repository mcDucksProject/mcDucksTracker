<?php

namespace App\Models;

use Database\Factories\TokenFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Token
 *
 * @property int             $id
 * @property string          $name
 * @property Carbon|null     $created_at
 * @property Carbon|null     $updated_at
 * @property string|null     $deleted_at
 * @mixin Eloquent
 * @property-read Collection $pairs
 * @property-read int|null   $pairs_count
 * @method static Builder|Token newModelQuery()
 * @method static Builder|Token newQuery()
 * @method static Builder|Token query()
 * @method static Builder|Token whereCreatedAt($value)
 * @method static Builder|Token whereId($value)
 * @method static Builder|Token whereName($value)
 * @method static Builder|Token whereUpdatedAt($value)
 * @method static TokenFactory factory(...$parameters)
 * @method static Builder|Token whereDeletedAt($value)
 */
class Token extends Model
{
    use HasFactory;

    function pairs(): HasMany
    {
        return $this->hasMany(Pair::class, "base_id");
    }
}
