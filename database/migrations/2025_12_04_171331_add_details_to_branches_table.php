<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('branches', function (Blueprint $table) {
        $table->string('logo')->nullable()->after('name');
        $table->text('email')->nullable()->after('logo');
        $table->text('phone')->nullable()->after('email');
        $table->string('address')->nullable()->after('phone');
        $table->longText('details')->nullable()->after('address');
    });
}

public function down()
{
    Schema::table('branches', function (Blueprint $table) {
        $table->dropColumn(['logo', 'email', 'phone', 'address', 'details']);
    });
}
}
