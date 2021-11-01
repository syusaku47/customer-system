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
            $table->increments('id')->comment('顧客見込みランクマスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->string('name', 10)->comment('顧客見込みランク名称');
            $table->string('abbreviation', 255)->nullable()->comment('顧客見込みランク略称');
            $table->integer('order')->default(999)->comment('顧客見込みランク順位');
            $table->tinyInteger('is_valid')->default(1)->comment('有効フラグ');
            $table->char('text_color',7)->comment('文字色');
            $table->char('background_color',7)->comment('背景色');
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
        Schema::dropIfExists('m_customer_estimated_ranks');
    }
}
