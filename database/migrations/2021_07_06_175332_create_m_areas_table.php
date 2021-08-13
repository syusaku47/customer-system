<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMAreasTable extends Migration
{
    /**
     * Run the migrations.<br>
     * エリアマスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_areas', function (Blueprint $table) {
            $table->increments('id')->comment('エリアマスタID');
            $table->string('store_name', 30)->nullable()->comment('店舗名');
            $table->string('name', 30)->nullable()->comment('エリア名称');
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
        Schema::dropIfExists('m_areas');
    }
}
