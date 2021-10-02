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
 * @property int         $id
 * @property string      $pair
 * @property float       $price
 * @property string      $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static TickerFactory factory(...$parameters)
 * @method static Builder|Ticker newModelQuery()
 * @method static Builder|Ticker newQuery()
 * @method static Builder|Ticker query()
 * @method static Builder|Ticker whereCreatedAt($value)
 * @method static Builder|Ticker whereDate($value)
 * @method static Builder|Ticker whereId($value)
 * @method static Builder|Ticker wherePair($value)
 * @method static Builder|Ticker wherePrice($value)
 * @method static Builder|Ticker whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Ticker extends Model
{
    use HasFactory;
}
