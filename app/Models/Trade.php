<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Trade
 *
 * @method static Builder|Trade newModelQuery()
 * @method static Builder|Trade newQuery()
 * @method static Builder|Trade query()
 * @mixin Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $portfolio_id
 * @property string $token
 * @property string $pair
 * @property float $expected_sell
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Trade whereCreatedAt($value)
 * @method static Builder|Trade whereDeletedAt($value)
 * @method static Builder|Trade whereExpectedSell($value)
 * @method static Builder|Trade whereId($value)
 * @method static Builder|Trade wherePair($value)
 * @method static Builder|Trade wherePortfolioId($value)
 * @method static Builder|Trade whereStatus($value)
 * @method static Builder|Trade whereToken($value)
 * @method static Builder|Trade whereUpdatedAt($value)
 * @method static Builder|Trade whereUserId($value)
 * @property-read \App\Models\Portfolio $portfolio
 * @property-read \App\Models\User $user
 */
class Trade extends Model
{
    use HasFactory;
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
