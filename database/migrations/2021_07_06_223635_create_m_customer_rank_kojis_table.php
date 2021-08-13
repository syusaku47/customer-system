<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCustomerRankKojisTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 顧客ランク（工事金額）マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_customer_rank_kojis', function (Blueprint $table) {
            $table->increments('customer_rank_koji_id')->comment('顧客ランク_工事金額マスタID');
            $table->string('name', 4)->comment('顧客ランク名称');
            $table->string('abbreviation', 1)->comment('顧客ランク略称');
            $table->string('text_color',7)->comment('文字色');
            $table->string('background_color',7)->comment('背景色');
            $table->decimal('amount',20, 2)->nullable()->comment('工事金額');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_customer_rank_kojis');
    }
}
