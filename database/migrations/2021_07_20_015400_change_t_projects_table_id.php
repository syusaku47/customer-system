<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTProjectsTableId extends Migration
{
    /**
     * Run the migrations.<br>
     * 案件データ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_projects', function (Blueprint $table) {
            // unsigned付与
            $table->integer('source_id')->nullable()->comment('発生源')->unsigned()->change();
            $table->integer('project_rank')->nullable()->comment('案件ランク（見込みランク）')->unsigned()->change();
            $table->integer('project_store')->nullable()->comment('案件担当店舗')->unsigned()->change();
            $table->integer('project_representative')->nullable()->comment('案件担当者')->unsigned()->change();
            $table->integer('failure_cause')->nullable()->comment('失注理由')->unsigned()->change();
            // 外部キー制約
            $table->foreign('customer_id')->references('id')->on('t_customers'); // 顧客データ
            $table->foreign('source_id')->references('id')->on('m_sources'); // 発生源マスタ
            $table->foreign('project_rank')->references('id')->on('m_project_ranks'); // 案件ランクマスタ
            $table->foreign('project_store')->references('id')->on('m_stores'); // 店舗マスタ
            $table->foreign('project_representative')->references('id')->on('m_employees'); // 社員マスタ
            $table->foreign('failure_cause')->references('id')->on('m_lostorders'); // 失注理由マスタ
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 外部キー制約削除
        Schema::table('t_projects', function (Blueprint $table) {
            $table->dropForeign('t_projects_customer_id_foreign');
            $table->dropForeign('t_projects_source_id_foreign');
            $table->dropForeign('t_projects_project_rank_foreign');
            $table->dropForeign('t_projects_project_store_foreign');
            $table->dropForeign('t_projects_project_representative_foreign');
            $table->dropForeign('t_projects_failure_cause_foreign');
        });
    }
}
