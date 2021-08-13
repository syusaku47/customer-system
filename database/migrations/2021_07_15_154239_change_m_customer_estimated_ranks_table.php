<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMCustomerEstimatedRanksTable extends Migration
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
            $table->string('abbreviation', 2)->comment('顧客見込みランク略称')->change();
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
