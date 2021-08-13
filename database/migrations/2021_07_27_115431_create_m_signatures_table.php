<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_signatures', function (Blueprint $table) {
            $table->increments('id')->comment('署名マスタID');
            $table->string('item', 255)->nullable()->comment('項目');
            $table->string('name', 30)->nullable()->comment('名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_signatures');
    }
}
