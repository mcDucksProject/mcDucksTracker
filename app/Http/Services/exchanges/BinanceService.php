<?php

namespace App\Http\Services\exchanges;

use App\Models\Pair;
use Carbon\Carbon;
use ccxt\binance;
use DateTime;
use Illuminate\Support\Collection;

class BinanceService
{
    private binance $binance;

    function __construct(binance $binance)
    {
        $this->binance = $binance;
    }

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

    /*
     * [
     *  [
            1504541580000, // UTC timestamp in milliseconds, integer
            4235.4,        // (O)pen price, float
            4240.6,        // (H)ighest price, float
            4230.0,        // (L)owest price, float
            4230.7,        // (C)losing price, float
            37.72941911    // (V)olume (in terms of the base currency), float
        ],
        ...
       ]
     */
    function getHistoricalData(
        Collection $pairs,
        string $timeframe = "1d",
        DateTime $since = null,
        int $limit = null
    ): Collection {
        return $pairs->map(function ($pair) use ($timeframe, $since, $limit) {

            sleep($this->binance->rateLimit / 1000);
            $data = collect($this->binance->fetch_ohlcv(
                $this->encodePair($pair),
                $timeframe,
                $since->getTimestamp() * 1000,
                $limit
            ))->map(function ($ohlcv) {
                $date = new Carbon($ohlcv[0] / 1000);
                return [
                    'date' => $date,
                    'price' => abs(($ohlcv[1] + $ohlcv[2]) / 2)
                ];
            });
            return [
                'pair' => $pair,
                'data' => $data
            ];
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
