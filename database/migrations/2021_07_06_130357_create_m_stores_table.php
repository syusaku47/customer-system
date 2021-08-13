<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMStoresTable extends Migration
{
    /**
     * Run the migrations.<br>
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_stores', function (Blueprint $table) {
            $table->increments('id')->comment('店舗マスタID');
            $table->string('name', 30)->comment('名称');
            $table->string('short_name', 15)->nullable()->comment('略称');
            $table->string('furigana', 50)->nullable()->comment('フリガナ');
            $table->string('tel_no', 20)->nullable()->comment('電話番号');
            $table->string('fax_no', 20)->nullable()->comment('FAX番号');
            $table->string('post_no', 7)->nullable()->comment('郵便番号');
            $table->string('prefecture', 10)->nullable()->comment('都道府県');
            $table->string('city', 30)->nullable()->comment('市区町村');
            $table->string('address', 60)->nullable()->comment('地名・番地');
            $table->string('building_name', 64)->nullable()->comment('建築名等');
            $table->tinyInteger('is_valid')->nullable()->comment('有効フラグ');
            $table->string('free_dial', 20)->nullable()->comment('フリーダイヤル');
            $table->string('bank_name', 30)->nullable()->comment('銀行名');
            $table->string('bank_account_no', 30)->nullable()->comment('口座番号');
            $table->string('holder', 30)->nullable()->comment('名義');
            $table->tinyInteger('bank_account')->nullable()->comment('口座');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_stores');
    }
}
