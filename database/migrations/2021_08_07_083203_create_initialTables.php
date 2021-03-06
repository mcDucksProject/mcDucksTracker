<?php

use App\Models\Exchange;
use App\Models\Order;
use App\Models\Pair;
use App\Models\Portfolio;
use App\Models\Position;
use App\Models\Token;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Token::class, 'base_id')->constrained('tokens');
            $table->foreignIdFor(Token::class, 'quote_id')->constrained('tokens');
            $table->unique(['base_id', 'quote_id']);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Exchange::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Portfolio::class)->constrained();
            $table->foreignIdFor(Token::class)->constrained();
            $table->enum('status', ['open', 'closed']);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Position::class)->constrained();
            $table->double('quantity');
            $table->enum('status', ['filled', 'open']);
            $table->enum('type', ['buy', 'sell']);
            $table->timestamp('order_date');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('order_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Pair::class)->constrained();
            $table->double('price');
            $table->boolean('auto_calculated',)->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('historical_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pair::class);
            $table->double('price');
            $table->timestamp('price_date');
            $table->timestamps();
        });
        Schema::create('tickers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pair::class);
            $table->double('price');
            $table->timestamp('ticker_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
}
