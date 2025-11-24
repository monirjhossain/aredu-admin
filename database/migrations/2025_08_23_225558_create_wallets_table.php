<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
      {
        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable(); // e.g. Student Wallet, Office Wallet
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->decimal('balance', 20, 4)->default(0); // wallet balance in its own currency
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->enum('type', ['credit', 'debit']); // credit = add money, debit = deduct
            $table->decimal('amount', 20, 4); // transaction amount
            $table->decimal('balance_after', 20, 4); // balance after transaction
            $table->string('reference')->nullable(); // e.g. invoice ID, expense ID
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
    }
};