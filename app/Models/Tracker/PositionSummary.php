<?php

namespace App\Models\Tracker;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PositionSummary
{
    private Token $base;
    private float $quantity;
    private Collection $quotesSummary;
    private Carbon $startDate;

    public function __construct()
    {
        $this->quotesSummary = new Collection(QuoteSummary::class);
        $this->quantity = 0;
    }

    public function getBase(): Token
    {
        return $this->base;
    }

    public function setBase(Token $base): PositionSummary
    {
        $this->base = $base;
        return $this;
    }

    public function getQuotesSummary(): Collection
    {
        return $this->quotesSummary;
    }

    public function setQuotesSummary(Collection $quotesSummary): PositionSummary
    {
        $this->quotesSummary = $quotesSummary;
        return $this;
    }

    public function addQuoteSummary(QuoteSummary $quotesSummary): PositionSummary
    {
        $this->quotesSummary->add($quotesSummary);
        return $this;
    }

    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    public function setStartDate(Carbon $startDate): PositionSummary
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): PositionSummary
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function addQuantity(float $quantity): PositionSummary
    {
        $this->quantity += $quantity;
        return $this;
    }
}
