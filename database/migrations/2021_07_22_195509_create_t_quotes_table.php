<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTQuotesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 見積データ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_quotes', function (Blueprint $table) {
            $table->increments('id')->comment('見積ID');
            $table->integer('project_id')->comment('案件ID')->unsigned();
//            $table->integer('company_id')->comment('会社ID')->unsigned(); // TODO 後で追加
            $table->tinyInteger('order_flag')->nullable()->comment('受注フラグ'); // 1:発注案件を含む 0:発注案件を含まない
            $table->char('quote_no',10)->nullable()->comment('見積番号');
            $table->date('quote_date')->nullable()->comment('見積作成日');
            $table->integer('quote_creator')->nullable()->comment('見積作成者')->unsigned();
            $table->decimal('quote_price', 20, 2)->nullable()->comment('見積金額');
            $table->decimal('tax_amount_quote', 20, 2)->nullable()->comment('消費税額（見積）');
            $table->decimal('including_tax_total_quote', 20, 2)->nullable()->comment('税込合計見積');
            $table->decimal('cost_sum', 20, 2)->nullable()->comment('原価合計');
            $table->decimal('tax_amount_cost', 20, 2)->nullable()->comment('消費税額（原価）');
            $table->decimal('including_tax_total_cost', 20, 2)->nullable()->comment('税込合計原価');
            $table->bigInteger('adjustment_amount')->nullable()->comment('調整額');
            $table->date('quote_expiration_date')->nullable()->comment('見積有効期限');
            $table->date('order_expected_date')->nullable()->comment('発注予定日');
            $table->string('remarks', 500)->nullable()->comment('備考');
            $table->decimal('field_cooperating_cost_estimate', 20, 2)->nullable()->comment('現場協力費（見積）%');
            $table->decimal('field_cooperating_cost', 20, 2)->nullable()->comment('現場協力費（原価）%');
            $table->decimal('call_cost_estimate', 20, 2)->nullable()->comment('呼び原価（見積）%');
            $table->decimal('call_cost', 20, 2)->nullable()->comment('呼び原価（原価）%');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->string('last_updated_by')->comment('最終更新者');
            // 外部キー成約
//            $table->foreign('project_id')->references('id')->on('t_projects');
//            $table->foreign('company_id')->references('id')->on('t_projects'); // TODO 後で追加
//            $table->foreign('quote_creator')->references('id')->on('m_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_quotes');
    }
}
