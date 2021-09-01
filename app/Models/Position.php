<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Trade
 *
 * @method static Builder|Position newModelQuery()
 * @method static Builder|Position newQuery()
 * @method static Builder|Position query()
 * @mixin Eloquent
 * @property int            $id
 * @property int            $user_id
 * @property int            $portfolio_id
 * @property string         $token
 * @property string         $pair
 * @property float          $expected_sell
 * @property string         $status
 * @property Carbon|null    $created_at
 * @property Carbon|null    $updated_at
 * @property string|null    $deleted_at
 * @method static Builder|Position whereCreatedAt($value)
 * @method static Builder|Position whereDeletedAt($value)
 * @method static Builder|Position whereExpectedSell($value)
 * @method static Builder|Position whereId($value)
 * @method static Builder|Position wherePair($value)
 * @method static Builder|Position wherePortfolioId($value)
 * @method static Builder|Position whereStatus($value)
 * @method static Builder|Position whereToken($value)
 * @method static Builder|Position whereUpdatedAt($value)
 * @method static Builder|Position whereUserId($value)
 * @property-read Portfolio $portfolio
 * @property-read User      $user
 * @method static \Database\Factories\PositionFactory factory(...$parameters)
 */
class Position extends Model
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

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
