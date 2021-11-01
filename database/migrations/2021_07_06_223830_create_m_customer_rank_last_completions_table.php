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
            $table->increments('id')->comment('顧客ランク_最終完工日マスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->string('name', 10)->comment('顧客ランク名称');
            $table->string('abbreviation', 255)->comment('顧客ランク略称');
            $table->integer('date')->nullable()->comment('最終完工日');
            $table->tinyInteger('is_valid')->default(1)->comment('有効フラグ');
            $table->integer('order')->default(999)->comment('表示順');
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
        Schema::dropIfExists('m_customer_rank_last_completions');
    }
}
