<?php

namespace App\Models;

use App\Http\Scopes\UserScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * App\Models\Position
 *
 * @property int            $id
 * @property int            $user_id
 * @property int            $portfolio_id
 * @property int            $token_id
 * @property string         $status
 * @property Carbon|null    $created_at
 * @property Carbon|null    $updated_at
 * @property string|null    $deleted_at
 * @property-read Portfolio $portfolio
 * @property-read User      $user
 * @method static Builder|Position newModelQuery()
 * @method static Builder|Position newQuery()
 * @method static Builder|Position query()
 * @method static Builder|Position whereCreatedAt($value)
 * @method static Builder|Position whereDeletedAt($value)
 * @method static Builder|Position whereId($value)
 * @method static Builder|Position wherePortfolioId($value)
 * @method static Builder|Position whereStatus($value)
 * @method static Builder|Position whereTokenId($value)
 * @method static Builder|Position whereUpdatedAt($value)
 * @method static Builder|Position whereUserId($value)
 * @mixin \Eloquent
 */
class Position extends Model
{
    use HasFactory;

    public static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
