<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMKojiConfirmItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_koji_confirm_items', function (Blueprint $table) {
            $table->increments('id')->comment('工事確認項目マスタID');
            $table->string('item', 255)->nullable()->comment('項目');
            $table->string('caution', 255)->nullable()->comment('注意書き');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_koji_confirm_items');
    }
}
