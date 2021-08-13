<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTCustomerTableValidation extends Migration
{
    /**
     * Run the migrations.<br>
     * 顧客データ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_customers', function (Blueprint $table) {
            $table->string('name', 60)->comment('顧客_名称')->change();
            $table->string('keisho',4)->nullable()->comment('顧客_敬称')->change();
            $table->string('furigana', 60)->nullable()->comment('顧客_フリガナ')->change();
            $table->string('mail_address',254)->nullable()->comment('メールアドレス')->change();
            $table->string('mail_address2',254)->nullable()->comment('メールアドレス2')->change();
            $table->string('mail_address3',254)->nullable()->comment('メールアドレス3')->change();
            $table->string('city',50)->comment('住所_市区町村')->change();
            $table->string('address',50)->comment('住所_地名番地')->change();
            $table->string('building_name',100)->nullable()->comment('住所_建物名等')->change();
            $table->string('facebook_id',50)->nullable()->comment('FacebookID')->change();
            $table->string('twitter_id',15)->nullable()->comment('TwitterID')->change();
            $table->string('instagram_id',30)->nullable()->comment('InstagramID')->change();
            $table->string('remarks', 500)->nullable()->comment('備考')->change();
            $table->string('my_car_type_other', 20)->nullable()->comment('マイカー種別_その他')->change();
            $table->string('introducer', 60)->nullable()->comment('紹介者')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_customers', function (Blueprint $table) {
            //
        });
    }
}
