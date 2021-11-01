<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTFilesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * ファイルデータ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_files', function (Blueprint $table) {
            $table->increments('id')->comment('ファイルID');
            $table->integer('customer_id')->comment('顧客ID')->unsigned();
            $table->bigInteger('project_id')->comment('案件ID')->unsigned();
//            $table->integer('company_id')->comment('会社ID')->unsigned();
            $table->string('file_name', 40)->comment('ファイル名'); // ファイル拡張子を含まない
            $table->string('format', 10)->nullable()->comment('形式'); // ファイル拡張子
            $table->float('size')->nullable()->comment('サイズ');
            $table->string('comment', 500)->nullable()->comment('コメント');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->string('last_updated_by')->comment('最終更新者');
            // 外部キー制約
            $table->foreign('customer_id')->references('id')->on('t_quotes')->onDelete('cascade'); // 顧客データ
            $table->foreign('project_id')->references('id')->on('t_projects')->onDelete('cascade'); // 案件データ
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
        Schema::dropIfExists('t_files');
    }
}
