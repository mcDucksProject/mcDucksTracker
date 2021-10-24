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
 * @method static \Database\Factories\TickerFactory factory(...$parameters)
 * @method static Builder|Ticker newModelQuery()
 * @method static Builder|Ticker newQuery()
 * @method static Builder|Ticker query()
 * @mixin Eloquent
 */
class Ticker extends Model
{
    use HasFactory;
}
