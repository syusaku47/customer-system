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
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->date('start_date')->nullable()->comment('適用開始日');
            $table->decimal('tax_rate',12,2)->nullable()->default(0.0)->comment('消費税率');
            $table->tinyInteger('is_valid')->nullable()->comment('有効フラグ');
            $table->integer('order')->default(999)->comment('表示順');
            $table->unique(['company_id', 'internal_id']);
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
