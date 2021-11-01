<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Pair
 *
 * @property int         $id
 * @property int         $quote_id
 * @property int         $base_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Pair newModelQuery()
 * @method static Builder|Pair newQuery()
 * @method static Builder|Pair query()
 * @method static Builder|Pair whereBaseId($value)
 * @method static Builder|Pair whereCreatedAt($value)
 * @method static Builder|Pair whereId($value)
 * @method static Builder|Pair whereQuoteId($value)
 * @method static Builder|Pair whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $deleted_at
 * @property-read Token  $base
 * @property-read Token  $quote
 * @method static Builder|Pair whereDeletedAt($value)
 */
class Pair extends Model
{
    function base(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'base_id');
    }

    function quote(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'quote_id');
    }

    function ticker(): HasOne
    {
        return $this->hasOne(Ticker::class);
    }
}
