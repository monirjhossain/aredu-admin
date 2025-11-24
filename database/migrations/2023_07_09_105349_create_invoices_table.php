<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no')->nullable();
            $table->bigInteger('student_id')->nullable()->unsigned();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('products')->nullable();
            $table->text('documents')->nullable();
            $table->decimal('subtotal')->nullable();
            $table->decimal('discount')->nullable();

            
            $table->decimal('tax')->nullable();
            $table->decimal('grandtotal')->nullable();
            $table->decimal('paid')->nullable();

            $table->string('currency', 3)->default('BDT');
            $table->decimal('exchange_rate', 15, 6)->default(1); // rate to base currency
            $table->decimal('base_amount', 15, 2); // convert to currency amount

            $table->decimal('due')->nullable();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
