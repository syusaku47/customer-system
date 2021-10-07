<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Update3TFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_files', function (Blueprint $table) {
            DB::statement("ALTER TABLE `t_files` CHANGE `size` `size` DOUBLE(10,2) NULL DEFAULT NULL COMMENT 'サイズ'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_files', function (Blueprint $table) {
            //
        });
    }
}
