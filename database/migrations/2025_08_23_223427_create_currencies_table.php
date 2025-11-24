<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 3)->unique(); // ISO code: BDT, USD, RMB
            $table->string('name');              // Bangladeshi Taka, US Dollar, Chinese Yuan
            $table->string('symbol', 5)->nullable(); // ৳, $, ¥
            $table->boolean('is_base')->default(false); // mark system base currency
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
        Schema::dropIfExists('currencies');
    }
}
