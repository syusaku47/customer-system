<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMCustomerEstimatedRanksTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 顧客見込みランクマスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_customer_estimated_ranks', function (Blueprint $table) {
            $table->integer('order')->default(999)->comment('顧客見込みランク順位')->after('abbreviation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_customer_estimated_ranks', function (Blueprint $table) {
            //
        });
    }
}
