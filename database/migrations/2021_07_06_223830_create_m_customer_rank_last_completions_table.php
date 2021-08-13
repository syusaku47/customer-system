<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCustomerRankLastCompletionsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 顧客ランク（最終完工日）マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_customer_rank_last_completions', function (Blueprint $table) {
            $table->increments('customer_rank_last_completions_id')->comment('顧客ランク_最終完工日マスタID');
            $table->string('name', 4)->comment('顧客ランク名称');
            $table->string('abbreviation', 1)->comment('顧客ランク略称');
            $table->integer('date')->nullable()->comment('最終完工日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_customer_rank_last_completions');
    }
}
