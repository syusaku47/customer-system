<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTProjectsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_projects', function (Blueprint $table) {
            $table->string('lat', 20)->nullable()->comment('緯度')->after('remarks');
            $table->string('lng', 20)->nullable()->comment('経度')->after('lat');
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
