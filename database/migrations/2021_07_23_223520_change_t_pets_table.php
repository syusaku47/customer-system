<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTPetsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * ペットデータ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_pets', function (Blueprint $table) {
            // 外部キー制約
            $table->foreign('customer_id')->references('id')->on('t_customers'); // 顧客ID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_pets', function (Blueprint $table) {
            $table->dropForeign('t_pets_customer_id_foreign');
        });
    }
}
