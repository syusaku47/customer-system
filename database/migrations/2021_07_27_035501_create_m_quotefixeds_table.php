<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMQuotefixedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_quotefixs', function (Blueprint $table) {
            $table->increments('id')->comment('見積定型マスタID');
            $table->string('item', 255)->nullable()->comment('項目');
            $table->string('name', 30)->nullable()->comment('名称');
            $table->float('estimate')->nullable()->comment('見積');
            $table->float('cost')->nullable()->comment('原価');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_quotefixs');
    }
}
