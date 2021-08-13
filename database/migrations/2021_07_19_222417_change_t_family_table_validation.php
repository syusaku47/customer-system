<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTFamilyTableValidation extends Migration
{
    /**
     * Run the migrations.<br>
     * ご家族データ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_families', function (Blueprint $table) {
            $table->string('name', 60)->comment('氏名')->change();
            $table->string('relationship',5)->comment('続柄')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_families', function (Blueprint $table) {
            //
        });
    }
}
