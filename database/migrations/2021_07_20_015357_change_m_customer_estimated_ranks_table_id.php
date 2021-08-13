<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMCustomerEstimatedRanksTableId extends Migration
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
            // カラム名修正
            $table->renameColumn('customer_estimated_rank_id', 'id');
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
            // カラム名修正
            $table->renameColumn('id', 'customer_estimated_rank_id');
        });
    }
}
