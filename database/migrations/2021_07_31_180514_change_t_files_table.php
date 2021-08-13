<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_files', function (Blueprint $table) {
            $table->integer('project_id')->nullable()->comment('案件ID')->unsigned()->change();
            $table->dropForeign('t_files_customer_id_foreign');
        });
        Schema::table('t_files', function (Blueprint $table) {
            // 外部キー制約
            $table->foreign('customer_id')->references('id')->on('t_customers'); // 顧客データ
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_files', function (Blueprint $table) {
            //
        });
    }
}
