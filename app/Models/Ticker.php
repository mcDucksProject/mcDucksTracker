<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ticker
 *
 * @property int $id
 * @property string $pair
 * @property float $price
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\TickerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker wherePair($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticker whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ticker extends Model
{
    use HasFactory;
}
