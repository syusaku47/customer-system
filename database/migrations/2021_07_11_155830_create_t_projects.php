<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTProjects extends Migration
{
    /**
     * Run the migrations.<br>
     * 案件データ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_projects', function (Blueprint $table) {
            $table->increments('id')->comment('案件ID');
            $table->integer('customer_id')->comment('顧客ID')->unsigned();
            $table->string('field_name', 50)->nullable()->comment('現場名称');
            $table->string('name',50)->nullable()->comment('案件名');
            $table->string('field_address',255)->nullable()->comment('現場住所');
            $table->string('field_tel_no',20)->nullable()->comment('現場電話番号');
            $table->string('field_fax_no',20)->nullable()->comment('現場FAX');
            $table->date('construction_period_start')->nullable()->comment('受注工期（開始）');
            $table->date('construction_period_end')->nullable()->comment('受注工期（終了）');
            $table->date('construction_start_date')->nullable()->comment('着工予定日');
            $table->date('construction_date')->nullable()->comment('着工日');
            $table->date('completion_end_date')->nullable()->comment('完工予定日');
            $table->date('completion_date')->nullable()->comment('完工日');
            $table->integer('source_id')->nullable()->comment('発生源');
            $table->string('contract_no',50)->nullable()->comment('契約番号');
            $table->date('contract_date')->nullable()->comment('契約日');
            $table->date('cancel_date')->nullable()->comment('キャンセル日');
            $table->decimal('expected_amount',20, 2)->nullable()->comment('見込み金額');
            $table->decimal('order_price',20, 2)->nullable()->comment('受注金額（契約金額）');
            $table->integer('project_rank')->nullable()->comment('案件ランク（見込みランク）');
            $table->integer('project_store')->nullable()->comment('案件担当店舗');
            $table->integer('project_representative')->nullable()->comment('案件担当者');
            $table->char('post_no',7)->nullable()->comment('郵便番号');
            $table->string('prefecture',10)->nullable()->comment('都道府県');
            $table->string('city', 30)->nullable()->comment('市区町村');
            $table->string('address', 60)->nullable()->comment('地名、番地');
            $table->string('building_name', 64)->nullable()->comment('建物名等');
            $table->string('construction_parts', 255)->nullable()->comment('工事部位');
            $table->date('complete_date')->nullable()->comment('完了日');
            $table->date('failure_date')->nullable()->comment('失注日');
            $table->integer('failure_cause')->nullable()->comment('失注理由');
            $table->string('failure_remarks', 255)->nullable()->comment('失注備考');
            $table->string('cancel_reason', 255)->nullable()->comment('キャンセル理由');
            $table->tinyInteger('execution_end')->nullable()->comment('実行終了');
            $table->decimal('order_detail1', 20, 2)->nullable()->comment('受注詳細（追加1 – 最終原価）');
            $table->decimal('order_detail2', 20, 2)->nullable()->comment('受注詳細（追加2 – 最終原価）');
            $table->string('construction_status', 15)->nullable()->comment('工事状況');
            $table->tinyInteger('complete_flag', )->nullable()->comment('対応完了フラグ');
            $table->tinyInteger('alert_flag')->nullable()->comment('アラートフラグ');
            $table->string('remarks')->nullable()->comment('備考');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->string('last_updated_by')->comment('最終更新者');
            // 外部キー成約
//            $table->foreign('customer_id')->references('id')->on('t_customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_projects');
    }
}
