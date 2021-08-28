<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
use Database\Factories\PortfolioFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Portfolio
 *
 * @method static PortfolioFactory factory(...$parameters)
 * @method static Builder|Portfolio newModelQuery()
 * @method static Builder|Portfolio newQuery()
 * @method static Builder|Portfolio query()
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property int                                                   $user_id
 * @property Carbon|null                                           $created_at
 * @property Carbon|null                                           $updated_at
 * @property string|null                                           $deleted_at
 * @method static Builder|Portfolio whereCreatedAt($value)
 * @method static Builder|Portfolio whereDeletedAt($value)
 * @method static Builder|Portfolio whereId($value)
 * @method static Builder|Portfolio whereName($value)
 * @method static Builder|Portfolio whereUpdatedAt($value)
 * @method static Builder|Portfolio whereUserId($value)
 * @property-read Collection|Trade[] $trades
 * @property-read int|null                                         $trades_count
 * @property-read User                                             $user
 */
class Portfolio extends Model
{
    use HasFactory;

    public static function booted()
    {
        static::addGlobalScope(new UserScope());
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }
}
