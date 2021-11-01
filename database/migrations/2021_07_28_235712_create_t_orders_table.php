<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTOrdersTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 受注データ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_orders', function (Blueprint $table) {
            $table->increments('id')->comment('受注ID');
            $table->bigInteger('project_id')->comment('案件ID')->unsigned();
            $table->integer('quote_id')->nullable()->comment('見積ID')->unsigned();
//            $table->integer('company_id')->comment('会社ID')->unsigned();
            $table->date('contract_date')->nullable()->comment('契約日');
            $table->date('construction_start_date')->nullable()->comment('着工予定日');
            $table->date('completion_end_date')->nullable()->comment('完工予定日');
            $table->date('groundbreaking_ceremony')->nullable()->comment('着工式');
            $table->date('completion_based')->nullable()->comment('完工式');
            $table->decimal('contract_money', 20, 2)->nullable()->comment('契約金');
            $table->date('contract_billing_date')->nullable()->comment('契約金_請求日');
            $table->date('contract_expected_date')->nullable()->comment('契約金_入金予定日');
            $table->decimal('start_construction_money', 20, 2)->nullable()->comment('着工金');
            $table->date('start_construction_billing_date')->nullable()->comment('着工金_請求日');
            $table->date('start_construction_expected_date')->nullable()->comment('着工金_入金予定日');
            $table->decimal('intermediate_gold1', 20, 2)->nullable()->comment('中間金1');
            $table->date('intermediate1_billing_date')->nullable()->comment('中間金1_請求日');
            $table->date('intermediate1_expected_date')->nullable()->comment('中間金1_入金予定日');
            $table->decimal('intermediate_gold2', 20, 2)->nullable()->comment('中間金2');
            $table->date('intermediate2_billing_date')->nullable()->comment('中間金2_請求日');
            $table->date('intermediate2_expected_date')->nullable()->comment('中間金2_入金予定日');
            $table->decimal('completion_money', 20, 2)->nullable()->comment('完工金');
            $table->date('completion_billing_date')->nullable()->comment('完工金_請求日');
            $table->date('completion_expected_date')->nullable()->comment('完工金_入金予定日');
            $table->decimal('unallocated_money', 20, 2)->nullable()->comment('未割当金');
            $table->string('remarks', 500)->nullable()->comment('備考');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->string('last_updated_by')->comment('最終更新者');
            // 外部キー制約
//            $table->foreign('project_id')->references('id')->on('t_projects'); // 案件データ
//            $table->foreign('quote_id')->references('id')->on('t_quotes'); // 見積データ
//            $table->foreign('sales_contact')->references('id')->on('m_employees'); // 契約会社マスタ // TODO 後で追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_orders');
    }
}
