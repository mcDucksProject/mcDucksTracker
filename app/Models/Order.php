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
 * App\Models\Order
 *
 * @property int                       $id
 * @property int                       $user_id
 * @property int                       $position_id
 * @property float                     $quantity
 * @property Carbon                    $date
 * @property Carbon|null               $created_at
 * @property Carbon|null               $updated_at
 * @property string|null               $deleted_at
 * @property-read \App\Models\Position $holding
 * @property-read \App\Models\User     $user
 * @method static \Database\Factories\OrderFactory factory(...$parameters)
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

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
