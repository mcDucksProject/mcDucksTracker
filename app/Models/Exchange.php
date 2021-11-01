<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Exchange
 *
 * @property int                                                      $id
 * @property string                                                   $name
 * @method static Builder|Exchange newModelQuery()
 * @method static Builder|Exchange newQuery()
 * @method static Builder|Exchange query()
 * @method static Builder|Exchange whereId($value)
 * @method static Builder|Exchange whereName($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Exchange[] $portfolios
 * @property-read int|null                                            $portfolios_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Database\Factories\ExchangeFactory factory(...$parameters)
 * @method static Builder|Exchange whereCreatedAt($value)
 * @method static Builder|Exchange whereDeletedAt($value)
 * @method static Builder|Exchange whereUpdatedAt($value)
 */
class Exchange extends Model
{
    use HasFactory;

    function portfolios(): HasMany
    {
        return $this->hasMany(Exchange::class);
    }
}
