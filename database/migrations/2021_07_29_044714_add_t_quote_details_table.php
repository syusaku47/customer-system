<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTQuoteDetailsTable extends Migration
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
            $table->integer('index')->default(999)->comment('表示順')->after('remarks');
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
