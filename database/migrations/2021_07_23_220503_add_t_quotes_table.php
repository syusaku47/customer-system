<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTQuotesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 見積データ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_quotes', function (Blueprint $table) {
            $table->tinyInteger('is_editing')->default(0)->comment('編集中フラグ')->after('call_cost');
            $table->text('meisai')->comment('見積明細')->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_quotes', function (Blueprint $table) {
            $table->dropColumn('is_editing');
            $table->dropColumn('meisai');
        });
    }
}
