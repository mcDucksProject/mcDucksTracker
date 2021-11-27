<?php

namespace App\Models\Tracker;

use App\Models\Pair;
use Carbon\Carbon;

class PositionQuoteSummary
{
    private Pair $pair;
    private float $quantity = 0;
    private float $averageBuy = 0;
    private float $averageSell = 0;
    private float $currentPrice = 0;
    private float $invested = 0;

    private Carbon $lastTickerUpdate;

    public function getPnlInQuoteValue(): float
    {
        return ($this->invested * $this->getPnlInPercentage()) - $this->invested;
    }

    public function getPnlInPercentage(bool $formatted = false): float
    {
        $percentage = $this->getAverageBuy() != 0 ? $this->currentPrice / $this->getAverageBuy() : 0;
        if ($formatted) {
            return round(($percentage - 1) * 100, 2);
        }
        return $percentage;

    }

    public function getAverageBuy(): float
    {
        return $this->invested / $this->quantity;
    }

    public function addToAverageBuy(float $price, float $quantity)
    {
        //$this->averageBuy
    }

    public function getCurrentInvestmentValue(): float
    {
        return $this->quantity * $this->currentPrice;
    }

    /**
     * @return Carbon
     */
    public function getLastTickerUpdate(): Carbon
    {
        return $this->lastTickerUpdate;
    }

    public function setLastTickerUpdate(Carbon $lastTickerUpdate): PositionQuoteSummary
    {
        $this->lastTickerUpdate = $lastTickerUpdate;
        return $this;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): PositionQuoteSummary
    {
        $this->quantity = $quantity;
        return $this;
    }


    public function getAverageSell(): float
    {
        return $this->averageSell;
    }

    public function setAverageSell(float $averageSell): PositionQuoteSummary
    {
        $this->averageSell = $averageSell;
        return $this;
    }

    public function getCurrentPrice(): float
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(float $currentPrice): PositionQuoteSummary
    {
        $this->currentPrice = $currentPrice;
        return $this;
    }

    public function getInvested(): float
    {
        return $this->invested;
    }

    public function setInvested(float $invested): PositionQuoteSummary
    {
        $this->invested = $invested;
        return $this;
    }

    public function addInvested(float $invested): PositionQuoteSummary
    {
        $this->invested += $invested;
        return $this;
    }

    public function getPair(): Pair
    {
        return $this->pair;
    }

    public function setPair(Pair $pair): PositionQuoteSummary
    {
        $this->pair = $pair;
        return $this;
    }

}
