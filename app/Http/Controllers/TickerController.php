<?php

namespace App\Http\Controllers;

use App\Http\Services\TickerService;

class TickerController extends Controller
{
    private TickerService $tickerService;

    function __construct(TickerService $tickerService)
    {
        $this->tickerService = $tickerService;
    }

    function updateTickerData()
    {
        $this->tickerService->updateTickers();
    }
}
