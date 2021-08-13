<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTCustomersTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 顧客データ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_customers', function (Blueprint $table) {
            $table->string('keisho',10)->nullable()->comment('顧客_敬称')->change();
            $table->string('city',255)->comment('住所_市区町村')->change();
            $table->string('address',255)->comment('住所_地名番地')->change();
            $table->string('building_name',255)->nullable()->comment('住所_建物名等')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_customers', function (Blueprint $table) {
            //
        });
    }
}
