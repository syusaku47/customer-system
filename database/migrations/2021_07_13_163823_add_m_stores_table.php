<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMStoresTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 店舗マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_stores', function (Blueprint $table) {
            $table->binary('logo')->nullable()->comment('ロゴ')->after('bank_account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_stores', function (Blueprint $table) {
            //
        });
    }
}
