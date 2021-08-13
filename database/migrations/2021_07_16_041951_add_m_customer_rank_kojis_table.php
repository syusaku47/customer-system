<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMCustomerRankKojisTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 顧客ランク（工事金額）マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_customer_rank_kojis', function (Blueprint $table) {
            $table->integer('order')->default(999)->comment('顧客ランク順位')->after('abbreviation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_customer_rank_kojis', function (Blueprint $table) {
            //
        });
    }
}
