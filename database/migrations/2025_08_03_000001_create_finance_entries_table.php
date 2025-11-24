<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('finance_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['income', 'expense']);
            $table->date('transaction_date');
            $table->string('currency', 3)->default('BDT');
            $table->decimal('amount', 15, 2); // original entered amount
            $table->decimal('exchange_rate', 15, 6)->default(1); // rate to base currency
            $table->decimal('base_amount', 15, 2); // converted to BDT (or your system base currency)
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('category_id')->nullable();
            $table->string('categories')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('finance_entries');
    }
};
