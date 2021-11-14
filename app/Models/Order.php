<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
use Database\Factories\OrderFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;


/**
 * App\Models\Order
 *
 * @property int           $id
 * @property int           $user_id
 * @property int           $position_id
 * @property float         $quantity
 * @property Carbon|null   $created_at
 * @property Carbon|null   $updated_at
 * @property string|null   $deleted_at
 * @property-read User     $user
 * @method static OrderFactory factory(...$parameters)
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereDate($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order wherePositionId($value)
 * @method static Builder|Order whereQuantity($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUserId($value)
 * @mixin Eloquent
 * @property string                       $status
 * @property string                       $type
 * @property-read Position                $position
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereType($value)
 * @property-read Collection|OrderPrice[] $prices
 * @property-read int|null                $prices_count
 * @property Carbon $order_date
 * @method static Builder|Order whereOrderDate($value)
 */
class Order extends Model
{
    use HasFactory;

    protected $dates = ['date', 'created_at', 'updated_at'];
    protected $dateFormat = "Y-m-d H:i";

    public static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }


    public function prices(): HasMany
    {
        return $this->hasMany(OrderPrice::class);
    }
}
