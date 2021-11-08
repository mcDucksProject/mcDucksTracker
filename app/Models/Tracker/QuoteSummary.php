<?php

namespace App\Models\Tracker;

use App\Models\Token;

class QuoteSummary
{
    private Token $quote;
    private float $averageBuy;
    private float $averageSell;
    private float $actualPrice;
    private float $invested;
}
