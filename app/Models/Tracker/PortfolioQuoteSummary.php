<?php

namespace App\Models\Tracker;

use App\Models\Token;

class PortfolioQuoteSummary
{
    private Token $token;
    private float $investedValue;
    private float $currentValue;
}
