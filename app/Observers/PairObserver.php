<?php

namespace App\Observers;

use App\Exceptions\SaveException;
use App\Http\Services\HistoricalPriceService;
use App\Models\Pair;

class PairObserver
{
    private HistoricalPriceService $historicalPriceService;

    function __construct(HistoricalPriceService $historicalPriceService)
    {
        $this->historicalPriceService = $historicalPriceService;
    }

    /**
     * Handle the Pair "created" event.
     *
     * @param  Pair  $pair
     *
     * @return void
     */
    public function created(Pair $pair)
    {
        //
        try {
            $this->historicalPriceService->updateHistoricalPrice($pair);
        } catch (SaveException $e) {
            //@TODO add log exception logic
        }
    }

    /**
     * Handle the Pair "updated" event.
     *
     * @param  Pair  $pair
     *
     * @return void
     */
    public function updated(Pair $pair)
    {
        //
    }

    /**
     * Handle the Pair "deleted" event.
     *
     * @param  Pair  $pair
     *
     * @return void
     */
    public function deleted(Pair $pair)
    {
        //
    }

    /**
     * Handle the Pair "restored" event.
     *
     * @param  Pair  $pair
     *
     * @return void
     */
    public function restored(Pair $pair)
    {
        //
    }

    /**
     * Handle the Pair "force deleted" event.
     *
     * @param  Pair  $pair
     *
     * @return void
     */
    public function forceDeleted(Pair $pair)
    {
        //
    }
}
