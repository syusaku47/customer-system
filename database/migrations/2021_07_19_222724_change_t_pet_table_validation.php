<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTPetTableValidation extends Migration
{
    /**
     * Run the migrations.<br>
     * ペットデータ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_pets', function (Blueprint $table) {
            $table->string('name', 60)->comment('氏名')->change();
            $table->string('type', 10)->nullable()->comment('種別')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_pets', function (Blueprint $table) {
            //
        });
    }
}
