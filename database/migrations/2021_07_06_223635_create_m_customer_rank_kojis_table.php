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
            $table->increments('id')->comment('顧客ランク_工事金額マスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->string('name', 10)->comment('顧客ランク名称');
            $table->string('abbreviation', 255)->nullable()->comment('顧客ランク略称');
            $table->integer('order')->default(999)->comment('表示順');
            $table->char('text_color',7)->comment('文字色');
            $table->char('background_color',7)->comment('背景色');
            $table->integer('amount')->nullable()->comment('工事金額');
            $table->tinyInteger('is_valid')->default(1)->comment('有効フラグ');
            $table->unique(['company_id', 'internal_id']);


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
