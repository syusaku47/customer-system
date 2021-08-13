<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTQuoteDetailsTable extends Migration
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
//            $table->string('unit', 3)->nullable()->comment('単位')->unsigned(); // TODO 後で追加
            $table->integer('unit')->nullable()->comment('単位')->change();
            // 外部キー成約
//            $table->foreign('unit')->references('id')->on('m_credits'); // TODO 後で追加
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
