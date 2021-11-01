<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * App\Models\Ticker
 *
 * @method static \Database\Factories\TickerFactory factory(...$parameters)
 * @method static Builder|Ticker newModelQuery()
 * @method static Builder|Ticker newQuery()
 * @method static Builder|Ticker query()
 * @mixin Eloquent
 * @property int         $id
 * @property int         $pair_id
 * @property float       $price
 * @property string      $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Ticker whereCreatedAt($value)
 * @method static Builder|Ticker whereDate($value)
 * @method static Builder|Ticker whereId($value)
 * @method static Builder|Ticker wherePairId($value)
 * @method static Builder|Ticker wherePrice($value)
 * @method static Builder|Ticker whereUpdatedAt($value)
 */
class Ticker extends Model
{
    use HasFactory;

    function pair()
    {
        $this->belongsTo(Pair::class);
    }
}
