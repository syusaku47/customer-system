<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMMyCarTypesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * マイカー種別マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_my_car_types', function (Blueprint $table) {
            $table->increments('id')->comment('マイカー種別マスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->string('name', 255)->nullable()->comment('名称');
            $table->tinyInteger('is_valid')->default(1)->nullable()->comment('有効フラグ');
            $table->integer('order')->comment('表示順');
//            $table->unique(['company_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_my_car_types');
    }
}
