<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMProjectRanksTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 案件（見込み）ランクマスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_project_ranks', function (Blueprint $table) {
            $table->increments('id')->comment('案件ランクマスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->string('name', 10)->comment('案件ランク名称');
            $table->string('abbreviation', 255)->nullable()->comment('案件ランク略称');
            $table->integer('order')->default(999)->comment('案件ランク順位');
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
        Schema::dropIfExists('m_project_ranks');
    }
}
