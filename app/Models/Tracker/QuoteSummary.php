<?php

namespace App\Models\Tracker;

use App\Models\Pair;
use Carbon\Carbon;

class QuoteSummary
{
    private Pair $pair;
    private float $quantity = 0;
    private float $averageSell = 0;
    private float $actualPrice = 0;
    private float $invested = 0;
    private Carbon $lastTickerUpdate;

    public function getPnlInQuoteValue(): float
    {
        return ($this->invested * $this->getPnlInPercentage()) - $this->invested;
    }

    public function getPnlInPercentage(bool $formatted = false): float
    {
        $percentage = $this->getAverageBuy() != 0 ? $this->actualPrice / $this->getAverageBuy() : 0;
        if ($formatted) {
            return round(($percentage - 1) * 100, 2);
        }
        return $percentage;

    }

    public function getAverageBuy(): float
    {
        return $this->invested / $this->quantity;
    }

    /**
     * @return Carbon
     */
    public function getLastTickerUpdate(): Carbon
    {
        return $this->lastTickerUpdate;
    }

    /**
     * @param  Carbon  $lastTickerUpdate
     *
     * @return QuoteSummary
     */
    public function setLastTickerUpdate(Carbon $lastTickerUpdate): QuoteSummary
    {
        $this->lastTickerUpdate = $lastTickerUpdate;
        return $this;
    }


    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): QuoteSummary
    {
        $this->quantity = $quantity;
        return $this;
    }


    public function getAverageSell(): float
    {
        return $this->averageSell;
    }

    public function setAverageSell(float $averageSell): QuoteSummary
    {
        $this->averageSell = $averageSell;
        return $this;
    }

    public function getActualPrice(): float
    {
        return $this->actualPrice;
    }

    public function setActualPrice(float $actualPrice): QuoteSummary
    {
        $this->actualPrice = $actualPrice;
        return $this;
    }

    public function getInvested(): float
    {
        return $this->invested;
    }

    public function setInvested(float $invested): QuoteSummary
    {
        $this->invested = $invested;
        return $this;
    }

    public function addInvested(float $invested): QuoteSummary
    {
        $this->invested += $invested;
        return $this;
    }

    public function getPair(): Pair
    {
        return $this->pair;
    }

    public function setPair(Pair $pair): QuoteSummary
    {
        $this->pair = $pair;
        return $this;
    }

}
