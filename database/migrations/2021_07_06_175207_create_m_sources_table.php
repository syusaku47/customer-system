<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSourcesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 発生源マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sources', function (Blueprint $table) {
            $table->increments('id')->comment('発生源マスタID');
            $table->string('name', 30)->nullable()->comment('名称');
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
        Schema::dropIfExists('m_sources');
    }
}
