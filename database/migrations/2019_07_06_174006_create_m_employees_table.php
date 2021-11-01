<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMEmployeesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 社員マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_employees', function (Blueprint $table) {
            $table->increments('id')->comment('社員マスタID');
            $table->string('employee_cd', 8)->comment('社員CD');
            $table->string('password', 255)->nullable()->comment('パスワード');
            $table->integer('store_id')->unsigned()->comment('店舗ID');
            $table->string('name', 255)->nullable()->comment('社員名');
            $table->string('short_name', 255)->nullable()->comment('社員名_略称');
            $table->string('furigana',  255)->nullable()->comment('社員名_フリガナ');
            $table->string('job_title', 255)->nullable()->comment('役職名');
            $table->string('mail_address', 255)->nullable()->comment('メールアドレス');
            $table->integer('sales_target')->nullable()->default(0)->comment('売上目標');

            $table->integer('company_id')->unsigned()->default(1)->comment('会社ID');
            $table->tinyInteger('is_valid')->nullable()->default(1)->comment('有効フラグ');
            $table->tinyInteger('authority1')->nullable()->default(0)->comment('権限');
            $table->tinyInteger('authority2')->nullable()->default(0)->comment('権限2');
            $table->tinyInteger('authority3')->nullable()->default(0)->comment('権限3');
            $table->tinyInteger('authority4')->nullable()->default(0)->comment('権限4');
            $table->tinyInteger('access_5')->nullable()->default(0)->comment('権限5');
            $table->tinyInteger('access_6')->nullable()->default(0)->comment('権限6');
            $table->tinyInteger('access_7')->nullable()->default(0)->comment('権限7');
            $table->tinyInteger('access_8')->nullable()->default(0)->comment('権限8');
            $table->tinyInteger('access_9')->nullable()->default(0)->comment('権限9');
            $table->tinyInteger('access_10')->nullable()->default(0)->comment('権限10');
            $table->tinyInteger('role')->nullable()->comment('役割');
            $table->tinyInteger('status')->nullable()->comment('ステータス');
            $table->dateTime('expiration')->nullable()->comment('有効期限');
            $table->string('token')->nullable()->comment('トークン');
            $table->integer('order')->default(999)->comment('表示順');
            $table->foreign('store_id')->references('id')->on('m_stores')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('m_contract_companies')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_employees');
    }
}
