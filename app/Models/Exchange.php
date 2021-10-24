<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Exchange
 *
 * @property int    $id
 * @property string $name
 * @method static Builder|Exchange newModelQuery()
 * @method static Builder|Exchange newQuery()
 * @method static Builder|Exchange query()
 * @method static Builder|Exchange whereId($value)
 * @method static Builder|Exchange whereName($value)
 * @mixin \Eloquent
 */
class Exchange extends Model
{
    function portfolios(): HasMany
    {
        return $this->hasMany(Exchange::class);
    }
}
