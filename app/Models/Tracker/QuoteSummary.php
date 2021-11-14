<?php

namespace App\Models\Tracker;

use App\Models\Pair;
use App\Models\Token;

class QuoteSummary
{
    private Token $quote;
    private Pair $pair;
    private float $averageBuy;
    private float $averageSell;
    private float $actualPrice;
    private float $invested;

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function setQuote(Token $quote): QuoteSummary
    {
        $this->quote = $quote;
        return $this;
    }

    public function getAverageBuy(): float
    {
        return $this->averageBuy;
    }

    public function setAverageBuy(float $averageBuy): QuoteSummary
    {
        $this->averageBuy = $averageBuy;
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
