<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTQuoteDetails extends Migration
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
            $table->string('category_print_name', 50)->nullable()->comment('印刷名称（大分類名称）')->after('print_name');
            $table->string('sub_category_print_name', 50)->nullable()->comment('印刷名称（中分類名称）')->after('category_print_name');
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
