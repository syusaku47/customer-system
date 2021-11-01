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
            $table->integer('company_id')->comment('会社ID');
            $table->string('name', 255)->comment('名称');
            $table->string('short_name', 255)->nullable()->comment('略称');
            $table->string('furigana', 255)->nullable()->comment('フリガナ');
            $table->string('mail_address', 255)->nullable()->comment('メールアドレス');
            $table->string('tel_no', 255)->nullable()->comment('電話番号');
            $table->string('fax_no', 255)->nullable()->comment('FAX番号');
            $table->string('post_no', 7)->nullable()->comment('郵便番号');
            $table->string('jisx0401_code', 2)->nullable()->comment('JISX0401コード');
            $table->string('jisx0402_code', 5)->nullable()->comment('JISX0402コード');
            $table->integer('prefecture')->nullable()->comment('都道府県');
            $table->string('city', 255)->nullable()->comment('市区町村');
            $table->string('address', 255)->nullable()->comment('地名・番地');
            $table->string('building_name', 255)->nullable()->comment('建築名等');
            $table->text('bank_account')->nullable()->comment('振込先');
            $table->text('bank_account2')->nullable()->comment('振込先2');
            $table->text('bank_account3')->nullable()->comment('振込先3');
            $table->text('bank_account4')->nullable()->comment('振込先4');
            $table->text('bank_account5')->nullable()->comment('振込先5');
            $table->string('free_dial', 255)->nullable()->comment('フリーダイヤル');
            $table->tinyInteger('is_valid')->nullable()->default(1)->comment('有効フラグ');

            $table->string('holder', 255)->nullable()->comment('口座名義');
            $table->string('bank_name', 255)->nullable()->comment('銀行名');
            $table->string('bank_store_name', 255)->nullable()->comment('店舗名（銀行）');
            $table->tinyInteger('account')->nullable()->comment('口座');
            $table->string('bank_account_no', 255)->nullable()->comment('口座番号');

            $table->string('bank_name2', 255)->nullable()->comment('銀行名2');
            $table->string('bank_store_name2', 255)->nullable()->comment('店舗名（銀行）2');
            $table->tinyInteger('account2')->nullable()->comment('口座2');
            $table->string('bank_account_no2', 255)->nullable()->comment('口座番号2');

            $table->string('bank_name3', 255)->nullable()->comment('銀行名3');
            $table->string('bank_store_name3', 255)->nullable()->comment('店舗名（銀行）3');
            $table->tinyInteger('account3')->nullable()->comment('口座3');
            $table->string('bank_account_no3', 255)->nullable()->comment('口座番号3');

            $table->string('bank_name4', 255)->nullable()->comment('銀行名4');
            $table->string('bank_store_name4', 255)->nullable()->comment('店舗名（銀行）4');
            $table->tinyInteger('account4')->nullable()->comment('口座4');
            $table->string('bank_account_no4', 255)->nullable()->comment('口座番号4');

            $table->string('bank_name5', 255)->nullable()->comment('銀行名5');
            $table->string('bank_store_name5', 255)->nullable()->comment('店舗名（銀行）5');
            $table->tinyInteger('account5')->nullable()->comment('口座5');
            $table->string('bank_account_no5', 255)->nullable()->comment('口座番号5');

            $table->string('bank_name6', 255)->nullable()->comment('銀行名6');
            $table->string('bank_store_name6', 255)->nullable()->comment('店舗名（銀行）6');
            $table->tinyInteger('account6')->nullable()->comment('口座6');
            $table->string('bank_account_no6', 255)->nullable()->comment('口座番号6');

            $table->integer('order')->default(999)->comment('表示順');
            $table->text('logo')->nullable()->comment('PDF用ロゴ画像');

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
