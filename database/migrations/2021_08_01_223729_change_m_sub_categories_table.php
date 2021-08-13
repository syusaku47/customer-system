<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 中分類マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_sub_categories', function (Blueprint $table) {
            $table->renameColumn('category_name', 'category_id');
        });
        Schema::table('m_sub_categories', function (Blueprint $table) {
            $table->integer('category_id')->nullable(false)->comment('大分類マスタID')->unsigned()->change();
            // 外部キー定義
            $table->foreign('category_id')->references('id')->on('m_categories'); // 大分類マスタ
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
