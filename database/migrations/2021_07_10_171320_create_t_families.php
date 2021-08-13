<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTFamilies extends Migration
{
    /**
     * Run the migrations.<br>
     * ご家族データ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_families', function (Blueprint $table) {
            $table->increments('id')->comment('家族ID');
            $table->integer('customer_id')->comment('顧客ID')->unsigned();
            $table->string('name', 30)->comment('氏名');
            $table->string('relationship',3)->comment('続柄');
            $table->string('mobile_phone',20)->nullable()->comment('携帯電話');
            $table->date('birth_date')->nullable()->comment('生年月日');
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
        Schema::dropIfExists('t_families');
    }
}
