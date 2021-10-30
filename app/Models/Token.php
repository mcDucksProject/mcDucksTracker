<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Token
 *
 * @property int         $id
 * @property string      $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Token newModelQuery()
 * @method static Builder|Token newQuery()
 * @method static Builder|Token query()
 * @method static Builder|Token whereCreatedAt($value)
 * @method static Builder|Token whereId($value)
 * @method static Builder|Token whereName($value)
 * @method static Builder|Token whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Token extends Model
{
    function pairs(): HasMany
    {
        return $this->hasMany("pair","base_id");
    }
}
