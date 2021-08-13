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
            $table->string('employee_cd', 10)->comment('社員CD');
            $table->string('password', 50)->nullable()->comment('パスワード');
            $table->string('store_name', 50)->nullable()->comment('店舗名');
            $table->string('name', 30)->nullable()->comment('社員名');
            $table->string('short_name', 30)->nullable()->comment('社員名_略称');
            $table->string('furigana',  50)->nullable()->comment('社員名_フリガナ');
            $table->string('job_title', 30)->nullable()->comment('役職名');
            $table->string('mail_address', 50)->nullable()->comment('メールアドレス');
            $table->string('sales_target', 30)->nullable()->comment('売上目標');
            $table->tinyInteger('is_valid')->nullable()->comment('有効フラグ');
            $table->tinyInteger('authority1')->nullable()->comment('権限');
            $table->tinyInteger('authority2')->nullable()->comment('権限2');
            $table->tinyInteger('authority3')->nullable()->comment('権限3');
            $table->tinyInteger('authority4')->nullable()->comment('権限4');
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
