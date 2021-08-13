<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTProjectsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 案件データ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_projects', function (Blueprint $table) {
            $table->string('city', 255)->nullable()->comment('市区町村')->change();
            $table->string('address', 255)->nullable()->comment('地名、番地')->change();
            $table->string('building_name', 255)->nullable()->comment('建物名等')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_projects', function (Blueprint $table) {
            //
        });
    }
}
