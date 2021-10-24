<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pair
 *
 * @property int $id
 * @property int $quote_id
 * @property int $base_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Pair newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pair newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pair query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pair whereBaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pair whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pair whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pair whereQuoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pair whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pair extends Model
{

}
