<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\OrderPrice
 *
 * @property int   $id
 * @property int   $order_id
 * @property int   $user_id
 * @property int   $pair_id
 * @property float $price
 * @method static Builder|OrderPrice newModelQuery()
 * @method static Builder|OrderPrice newQuery()
 * @method static Builder|OrderPrice query()
 * @method static Builder|OrderPrice whereId($value)
 * @method static Builder|OrderPrice whereOrderId($value)
 * @method static Builder|OrderPrice wherePairId($value)
 * @method static Builder|OrderPrice wherePrice($value)
 * @method static Builder|OrderPrice whereUserId($value)
 * @mixin Eloquent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|OrderPrice whereCreatedAt($value)
 * @method static Builder|OrderPrice whereDeletedAt($value)
 * @method static Builder|OrderPrice whereUpdatedAt($value)
 * @property int         $auto_calculated
 * @method static Builder|OrderPrice whereAutoCalculated($value)
 * @property-read Order  $order
 * @property-read Pair   $pair
 * @property-read User   $user
 */
class OrderPrice extends Model
{
    public static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function pair(): BelongsTo
    {
        return $this->belongsTo(Pair::class);
    }
}
