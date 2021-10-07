<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_employees', function (Blueprint $table) {
            $table->renameColumn('store_name', 'store_id');
        });

        Schema::table('m_employees', function (Blueprint $table) {
            $table->integer('store_id')->nullable(false)->comment('店舗ID')->unsigned()->change();
        });

        Schema::table('m_employees', function (Blueprint $table) {
            //passwordをハッシュ化できるようにstringの長さ変更
            $table->string('password',255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_employees', function (Blueprint $table) {
            $table->dropForeign('m_employees_store_name_foreign');
        });
        Schema::table('m_employees', function (Blueprint $table) {
            $table->renameColumn('store_id', 'store_name');
        });
        Schema::table('m_employees', function (Blueprint $table) {
            $table->string('store_name',50)->nullable()->comment('店舗名')->change();
        });

        Schema::table('m_employees', function (Blueprint $table) {
            $table->string('password',50)->change();
        });
    }
}
