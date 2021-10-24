<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
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
 * @property int                                    $id
 * @property string                                 $name
 * @property int                                    $user_id
 * @property int                                    $exchange_id
 * @property Carbon|null                            $created_at
 * @property Carbon|null                            $updated_at
 * @property string|null                            $deleted_at
 * @property-read Collection|\App\Models\Position[] $holdings
 * @property-read int|null                          $holdings_count
 * @property-read \App\Models\User                  $user
 * @method static \Database\Factories\PortfolioFactory factory(...$parameters)
 * @method static Builder|Portfolio newModelQuery()
 * @method static Builder|Portfolio newQuery()
 * @method static Builder|Portfolio query()
 * @method static Builder|Portfolio whereCreatedAt($value)
 * @method static Builder|Portfolio whereDeletedAt($value)
 * @method static Builder|Portfolio whereExchangeId($value)
 * @method static Builder|Portfolio whereId($value)
 * @method static Builder|Portfolio whereName($value)
 * @method static Builder|Portfolio whereUpdatedAt($value)
 * @method static Builder|Portfolio whereUserId($value)
 * @mixin Eloquent
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

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}
