<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCustomerEstimatedRanksTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 顧客見込みランクマスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_customer_estimated_ranks', function (Blueprint $table) {
            $table->increments('customer_estimated_rank_id')->comment('顧客見込みランクマスタID');
            $table->string('name', 4)->comment('顧客見込みランク名称');
            $table->string('abbreviation', 1)->comment('顧客見込みランク略称');
            $table->string('text_color',7)->comment('文字色');
            $table->string('background_color',7)->comment('背景色');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_customer_estimated_ranks');
    }
}
