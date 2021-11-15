<?php

namespace App\Models;

use Database\Factories\TickerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * App\Models\Ticker
 *
 * @method static TickerFactory factory(...$parameters)
 * @method static Builder|Ticker newModelQuery()
 * @method static Builder|Ticker newQuery()
 * @method static Builder|Ticker query()
 * @mixin Eloquent
 * @property int         $id
 * @property int         $pair_id
 * @property float       $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Ticker whereCreatedAt($value)
 * @method static Builder|Ticker whereDate($value)
 * @method static Builder|Ticker whereId($value)
 * @method static Builder|Ticker wherePairId($value)
 * @method static Builder|Ticker wherePrice($value)
 * @method static Builder|Ticker whereUpdatedAt($value)
 * @property Carbon      $ticker_date
 * @method static Builder|Ticker whereTickerDate($value)
 */
class Ticker extends Model
{
    use HasFactory;

    protected $dates = ['ticker_date', 'created_at', 'updated_at'];

    function pair()
    {
        $this->belongsTo(Pair::class);
    }
}
