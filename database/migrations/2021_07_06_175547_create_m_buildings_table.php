<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMBuildingsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 建物分類マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_buildings', function (Blueprint $table) {
            $table->increments('id')->comment('建物分類マスタID');
            $table->string('name', 10)->nullable()->comment('名称');
            $table->tinyInteger('is_valid')->default(1)->nullable()->comment('有効フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_buildings');
    }
}
