<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMProjectRanksTableId extends Migration
{
    /**
     * Run the migrations.<br>
     * 案件ランクマスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_project_ranks', function (Blueprint $table) {
            // カラム名修正
            $table->renameColumn('project_rank_id', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_project_ranks', function (Blueprint $table) {
            // カラム名修正
            $table->renameColumn('id', 'project_rank_id');
        });
    }
}
