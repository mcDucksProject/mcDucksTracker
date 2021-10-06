<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
use Database\Factories\HoldingFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Trade
 *
 * @method static Builder|Holding newModelQuery()
 * @method static Builder|Holding newQuery()
 * @method static Builder|Holding query()
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
 * @method static Builder|Holding whereCreatedAt($value)
 * @method static Builder|Holding whereDeletedAt($value)
 * @method static Builder|Holding whereExpectedSell($value)
 * @method static Builder|Holding whereId($value)
 * @method static Builder|Holding wherePair($value)
 * @method static Builder|Holding wherePortfolioId($value)
 * @method static Builder|Holding whereStatus($value)
 * @method static Builder|Holding whereToken($value)
 * @method static Builder|Holding whereUpdatedAt($value)
 * @method static Builder|Holding whereUserId($value)
 * @property-read Portfolio $portfolio
 * @property-read User      $user
 * @method static HoldingFactory factory(...$parameters)
 * @property string         $symbol
 * @property string         $expected_sell_symbol
 * @method static Builder|Holding whereExpectedSellSymbol($value)
 * @method static Builder|Holding whereSymbol($value)
 */
class Holding extends Model
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
