<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMDetailsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 明細マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_details', function (Blueprint $table) {
            $table->integer('product_kubun')->comment('商品区分')->change();
            $table->integer('category_name')->comment('大分類')->change();
            $table->integer('subcategory_name')->comment('中分類')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_details', function (Blueprint $table) {
            //
        });
    }
}
