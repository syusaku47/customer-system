<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMaintenancesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * メンテナンスデータ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_maintenances', function (Blueprint $table) {
            $table->increments('id')->comment('メンテナンスID');
            $table->integer('customer_id')->comment('顧客ID')->unsigned();
            $table->integer('project_id')->comment('案件ID')->unsigned();
//            $table->integer('company_id')->comment('会社ID')->unsigned();
            $table->date('maintenance_date')->nullable()->comment('メンテナンス日');
            $table->tinyInteger('supported_kubun')->nullable()->comment('対応区分'); // 0:未対応 1:全て 2:対応済
            $table->string('title', 40)->nullable()->comment('メンテナンスタイトル');
            $table->date('supported_date')->nullable()->comment('対応日');
            $table->string('supported_content', 500)->nullable()->comment('対応内容');
            $table->string('detail', 500)->nullable()->comment('詳細内容');
            $table->tinyInteger('is_valid')->nullable()->default(1)->comment('有効フラグ'); // 0:無効 1:有効
            $table->string('lat', 20)->nullable()->comment('緯度');
            $table->string('lng', 20)->nullable()->comment('経度');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->string('last_updated_by')->comment('最終更新者');
            // 外部キー制約
//            $table->foreign('customer_id')->references('id')->on('t_customers'); // 顧客データ
//            $table->foreign('project_id')->references('id')->on('t_projects'); // 案件データ
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
        Schema::dropIfExists('t_maintenances');
    }
}
