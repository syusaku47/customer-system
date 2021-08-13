<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMMadorisTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 間取りマスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_madoris', function (Blueprint $table) {
            $table->increments('id')->comment('間取りマスタID');
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
        Schema::dropIfExists('m_madoris');
    }
}
