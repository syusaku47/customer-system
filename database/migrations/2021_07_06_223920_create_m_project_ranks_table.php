<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMProjectRanksTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 案件ランクマスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_project_ranks', function (Blueprint $table) {
            $table->increments('project_rank_id')->comment('案件ランクマスタID');
            $table->string('name', 4)->comment('案件ランク名称');
            $table->string('abbreviation', 2)->comment('案件ランク略称');
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
        Schema::dropIfExists('m_project_ranks');
    }
}
