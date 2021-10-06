<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
use Database\Factories\OrderFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * App\Models\Order
 *
 * @property int          $id
 * @property int          $user_id
 * @property int          $position_id
 * @property float        $quantity
 * @property float        $price
 * @property Carbon|null  $date
 * @property Carbon|null  $created_at
 * @property Carbon|null  $updated_at
 * @property string|null  $deleted_at
 * @property-read Holding $position
 * @property-read User    $user
 * @method static OrderFactory factory(...$parameters)
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereDate($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order wherePositionId($value)
 * @method static Builder|Order wherePrice($value)
 * @method static Builder|Order whereQuantity($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUserId($value)
 * @mixin Eloquent
 * @property int          $holding_id
 * @property float        $price_btc
 * @property float        $price_usdt
 * @property-read Holding $holding
 * @method static Builder|Order whereHoldingId($value)
 * @method static Builder|Order wherePriceBtc($value)
 * @method static Builder|Order wherePriceUsdt($value)
 */
class Order extends Model
{
    use HasFactory;

    protected $dates = ['date', 'created_at', 'udpated_at'];
    protected $dateFormat = "Y-m-d H:i";

    public static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function holding(): BelongsTo
    {
        return $this->belongsTo(Holding::class);
    }
}
