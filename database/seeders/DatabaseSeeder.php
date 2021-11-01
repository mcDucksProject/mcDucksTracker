<?php

namespace Database\Seeders;

use App\Models\Exchange;
use App\Models\Portfolio;
use App\Models\Token;
use App\Models\User;
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
        Token::factory(1)->create(
            [
                'name' => 'BTC'
            ]
        );
        Token::factory(1)->create(
            [
                'name' => 'USDT'
            ]
        );
    }
}
