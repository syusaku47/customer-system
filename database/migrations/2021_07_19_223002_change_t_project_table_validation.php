<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTProjectTableValidation extends Migration
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
            $table->string('field_name', 60)->nullable()->comment('現場名称')->change();
            $table->string('name',40)->nullable()->comment('案件名')->change();
            $table->string('city', 50)->nullable()->comment('市区町村')->change();
            $table->string('address', 50)->nullable()->comment('地名、番地')->change();
            $table->string('building_name', 100)->nullable()->comment('建物名等')->change();
            $table->string('remarks', 500)->nullable()->comment('備考')->change();
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
