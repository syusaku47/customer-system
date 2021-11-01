<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTSupportsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 対応履歴データ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_supports', function (Blueprint $table) {
            $table->increments('id')->comment('対応履歴ID');
            $table->integer('customer_id')->comment('顧客ID')->unsigned();
            $table->integer('project_id')->nullable()->comment('案件ID')->unsigned();
//            $table->integer('company_id')->comment('会社ID')->unsigned(); // TODO 後で追加
            $table->date('reception_date')->nullable()->comment('受付日');
            $table->tinyInteger('supported_kubun')->nullable()->comment('対応区分'); // 0:未対応 1:全て 2:対応済
            $table->string('supported_content', 500)->nullable()->comment('対応内容');
            $table->string('detail', 500)->nullable()->comment('詳細内容');
            $table->date('supported_date')->nullable()->comment('対応日');
            $table->integer('category')->nullable()->comment('カテゴリ')->unsigned(); // 対応履歴マスタのID
            $table->integer('supported_responsible_store')->nullable()->comment('対応担当店舗')->unsigned(); // 店舗マスタのID
            $table->integer('supported_representative')->nullable()->comment('対応担当担当者')->unsigned(); // 社員マスタのID
            $table->timestamp('reception_time')->comment('受付日時'); // 対応履歴日時
            $table->string('supported_history_name', 40)->nullable()->comment('対応履歴名'); // 件名
            $table->integer('supported_person')->nullable()->comment('対応者'); // 社員マスタのID
            $table->date('supported_complete_date')->nullable()->comment('対応完了日');
            $table->tinyInteger('is_fixed')->nullable()->comment('対応済みフラグ'); // 0:未対応 1:全て 2:対応済
            $table->integer('media')->nullable()->comment('媒体'); // 1:対面 2:電話 3:LINE 4:facebook 5:FAX 6:その他
            $table->binary('image')->nullable()->comment('画像');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->string('last_updated_by')->comment('最終更新者');
            // 外部キー制約
//            $table->foreign('customer_id')->references('id')->on('t_customers'); // 顧客データ
//            $table->foreign('project_id')->references('id')->on('t_projects'); // 案件データ
//            $table->foreign('category')->references('id')->on('m_supports'); // 対応履歴マスタ // TODO 後で追加
//            $table->foreign('supported_responsible_store')->references('id')->on('m_stores'); // 店舗マスタ
//            $table->foreign('supported_representative')->references('id')->on('m_employees'); // 社員マスタ
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
        Schema::dropIfExists('t_supports');
    }
}
