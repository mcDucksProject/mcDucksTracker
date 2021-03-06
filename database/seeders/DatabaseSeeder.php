<?php

namespace Database\Seeders;

use App\Models\Exchange;
use App\Models\Pair;
use App\Models\Portfolio;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create(
            [
                'name' => 'Luke Skywalker',
                'email' => 'luke@jedi.com',
                'email_verified_at' => null,
            ]
        );
        User::factory(1)->create(
            [
                'name' => 'Leia Organa',
                'email' => 'leia@jedi.com',
                'email_verified_at' => null
            ]
        );
        Exchange::factory(1)->create(
            [
                'name' => 'Binance'
            ]
        );
        Portfolio::factory(1)->create(
            [
                'name' => 'Test',
                'user_id' => 1,
                'exchange_id' => 1
            ]
        );

        Token::insert([
            ['name' => 'BTC'],
            ['name' => 'USDT'],
            ['name' => 'EUR'],
            ['name' => 'MIR'],
            ['name' => 'ICP'],
            ['name' => 'YFI'],
            ['name' => 'BNB'],
            ['name' => 'ADA'],
            ['name' => 'ROSE'],
            ['name' => 'ZIL'],
            ['name' => 'SHIB'],
            ['name' => 'BTT'], //12
            ['name' => 'EOS'], //13
            ['name' => 'XTZ'], //14
            ['name' => 'DOT'],
            ['name' => 'IOTA'],
        ]);
        $tokens = Token::whereNotIn('name',
            ['BTC', 'USDT', 'EUR', 'SHIB', 'BTT', 'EOS', 'XTZ']
        )->get();
        $date = new Carbon();
        $pairs = $tokens->flatMap(function ($token) use ($date) {
            return [
                [
                    'quote_id' => 1,
                    'base_id' => $token->id,
                    'created_at' => $date,
                    'updated_at' => $date
                ],
                [
                    'quote_id' => 2,
                    'base_id' => $token->id,
                    'created_at' => $date,
                    'updated_at' => $date
                ]
            ];
        })->toArray();
        Pair::insert($pairs);
        Pair::insert([
            [
                'quote_id' => 2,
                'base_id' => 11,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'quote_id' => 7,
                'base_id' => 5,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'quote_id' => 2,
                'base_id' => 1,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'quote_id' => 2,
                'base_id' => 12,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'quote_id' => 2,
                'base_id' => 13,
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'quote_id' => 2,
                'base_id' => 14,
                'created_at' => $date,
                'updated_at' => $date
            ],
        ]);
    }
}
