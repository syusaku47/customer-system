<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMPartsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 部位マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_parts', function (Blueprint $table) {
            $table->increments('id')->comment('部位マスタID');
            $table->string('name', 30)->nullable()->comment('名称');
            $table->tinyInteger('is_input')->default(0)->nullable()->comment('テキスト入力有無フラグ');
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
        Schema::dropIfExists('m_parts');
    }
}
