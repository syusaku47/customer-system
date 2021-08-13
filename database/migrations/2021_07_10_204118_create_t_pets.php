<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTPets extends Migration
{
    /**
     * Run the migrations.<br>
     * ペットデータ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_pets', function (Blueprint $table) {
            $table->increments('id')->comment('ペットID');
            $table->integer('customer_id')->comment('顧客ID')->unsigned();
            $table->string('name', 30)->comment('氏名');
            $table->string('type', 30)->nullable()->comment('種別');
            $table->tinyInteger('sex')->nullable()->comment('性別');
            $table->integer('age')->nullable()->comment('才');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            // 外部キー成約
//            $table->foreign('customer_id')->references('id')->on('t_customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_pets');
    }
}
