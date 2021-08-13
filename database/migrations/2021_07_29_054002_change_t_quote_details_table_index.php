<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTQuoteDetailsTableIndex extends Migration
{
    /**
     * Run the migrations.<br>
     * 見積明細データ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_quote_details', function (Blueprint $table) {
            $table->renameColumn('index', 'category_index');
        });
        Schema::table('t_quote_details', function (Blueprint $table) {
            $table->integer('category_index')->default(999)->comment('大分類表示順')->after('remarks')->change();
            $table->integer('sub_category_index')->default(999)->comment('中分類表示順')->after('category_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_quote_details', function (Blueprint $table) {
            //
        });
    }
}
