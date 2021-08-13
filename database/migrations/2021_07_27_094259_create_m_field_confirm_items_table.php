<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMFieldConfirmItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_field_confirm_items', function (Blueprint $table) {
            $table->increments('id')->comment('現場準備確認項目マスタID');
            $table->string('item', 255)->nullable()->comment('項目');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_field_confirm_items');
    }
}
