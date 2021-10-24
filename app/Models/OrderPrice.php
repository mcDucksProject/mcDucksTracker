<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
 */
class OrderPrice extends Model
{
    public static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    function order()
    {
        $this->belongsTo(Order::class);
    }

    function user()
    {
        $this->belongsTo(User::class);
    }

    function pair()
    {
        $this->belongsTo(Pair::class);
    }
}
