<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCustomerRankLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_customer_rank_logs', function (Blueprint $table) {
            $table->increments('id')->comment('顧客ランク自動更新ログデータID');
            $table->integer('customer_id')->comment('顧客ID')->unsigned();
            $table->string('customer_name',60)->nullable()->comment('顧客_名称');
            $table->integer('sales_contact')->nullable()->comment('営業担当（担当者）')->unsigned();
            $table->string('customer_rank_before_change',8)->nullable()->comment('変更前顧客ランク');
            $table->string('customer_rank_after_change',8)->nullable()->comment('変更後顧客ランク');
            $table->decimal('total_work_price', 20, 2)->nullable()->comment('総工事金額');
            $table->integer('total_work_times')->nullable()->comment('総工事回数');
            $table->date('last_completion_date', 4)->nullable()->comment('最終完工日');
            $table->date('updated_date')->nullable()->comment('更新日');
            // 外部キー制約
            $table->foreign('customer_id')->references('id')->on('t_customers'); // 顧客データ
            $table->foreign('sales_contact')->references('id')->on('m_employees'); // 社員マスタ
        });
        DB::statement("ALTER TABLE t_customer_rank_logs COMMENT '顧客ランク自動更新ログデータ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_customer_rank_logs');
    }
}
