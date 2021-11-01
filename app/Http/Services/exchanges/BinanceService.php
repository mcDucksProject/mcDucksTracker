<?php

namespace App\Http\Services\exchanges;

use App\Models\Pair;
use ccxt\binance;
use Illuminate\Support\Collection;

class BinanceService
{
    private binance $binance;

    function __construct(binance $binance)
    {
        $this->binance = $binance;
    }

    /**
     * @param  Collection  $pairs
     */
    function getTickersData(Collection $pairs): Collection
    {

        $encodedPairs = $pairs->map(function ($pair) {
            /** @var Pair $pair */
            return $this->encodePair($pair);
        });
        $rawTickers = collect($this->binance->fetch_tickers($encodedPairs->all()));
        return $rawTickers->map(function ($rawTicker) use ($pairs) {
            return $this->parseTickerValue($rawTicker, $pairs);
        });
    }

    private function encodePair(Pair $pair): string
    {
        return $pair->base->name . '/' . $pair->quote->name;
    }

    private function parseTickerValue(array $rawTicker, Collection $pairs): array
    {
        $decodedPair = $this->decodePair($rawTicker['symbol'], $pairs);
        return [
            'pair' => $decodedPair,
            'price' => $rawTicker['last']
        ];
    }

    private function decodePair(string $encodedPair, Collection $pairs): Pair
    {
        $decodedPair = explode("/", $encodedPair);
        $baseName = $decodedPair[0];
        $quoteName = $decodedPair[1];
        return $pairs->first(function ($pair) use ($baseName, $quoteName) {
            /** @var Pair $pair */
            return $pair->base->name == $baseName && $pair->quote->name == $quoteName;
        });
    }


}
