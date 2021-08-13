<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMAfterMaintenancesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * アフターメンテナンスマスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_after_maintenances', function (Blueprint $table) {
            $table->integer('ins_expected_date')->nullable()->comment('登録予定日（月）')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_after_maintenances', function (Blueprint $table) {
            //
        });
    }
}
