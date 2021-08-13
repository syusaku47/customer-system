<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMLostordersTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 失注理由マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_lostorders', function (Blueprint $table) {
            $table->increments('id')->comment('失注理由マスタID');
            $table->string('lost_reason', 30)->nullable()->comment('失注理由');
            $table->tinyInteger('is_valid')->nullable()->default(1)->comment('有効フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_lostorders');
    }
}
