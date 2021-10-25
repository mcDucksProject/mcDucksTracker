<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\HistoricalPrice
 *
 * @property int         $id
 * @property int         $pair_id
 * @property float       $price
 * @property string      $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|HistoricalPrice newModelQuery()
 * @method static Builder|HistoricalPrice newQuery()
 * @method static Builder|HistoricalPrice query()
 * @method static Builder|HistoricalPrice whereCreatedAt($value)
 * @method static Builder|HistoricalPrice whereDate($value)
 * @method static Builder|HistoricalPrice whereId($value)
 * @method static Builder|HistoricalPrice wherePairId($value)
 * @method static Builder|HistoricalPrice wherePrice($value)
 * @method static Builder|HistoricalPrice whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read \App\Models\Pair $pair
 */
class HistoricalPrice extends Model
{
    function pair(): BelongsTo
    {
        return $this->belongsTo(Pair::class);
    }
}
