<?php

namespace App\Http\Services\Tracker;

use App\Models\Position;
use App\Models\Tracker\QuoteSummary;
use Ramsey\Collection\Collection;

class PositionSummaryService
{
    function calculatePositionStatus(Position $position){
        $orders = $position->orders();
        $pairs = $position->token->pairs;
        $buyOrders = $orders->where('type', '=','buy');
        /** @TODO calculate sell orders */
        $positionQuotes = new Collection(QuoteSummary::class);
        $pairs->each(function($pair){

        });
    }
    private function calculateAverageBuy(Collection $buyOrders): float {

    }
}
