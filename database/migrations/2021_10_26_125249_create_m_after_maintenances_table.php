<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMAfterMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_after_maintenances', function (Blueprint $table) {
            $table->increments('id')->comment('アフターメンテナンスマスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->integer('ins_expected_date')->nullable()->comment('登録予定日(月)');
            $table->tinyInteger('is_valid')->nullable()->comment('有効フラグ');
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
        Schema::dropIfExists('m_after_maintenances');
    }
}
