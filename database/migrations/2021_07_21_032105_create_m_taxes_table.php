<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_taxes', function (Blueprint $table) {
            $table->increments('id')->comment('消費税マスタID');
            $table->date('start_date')->nullable()->comment('適用開始日');
            $table->float('tax_rate')->nullable()->comment('消費税率');
            $table->tinyInteger('is_valid')->nullable()->comment('有効フラグ');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_taxes');
    }
}
