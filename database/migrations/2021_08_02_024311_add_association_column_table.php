<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssociationColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_files', function (Blueprint $table) {
            Schema::table('t_files', function (Blueprint $table) {
                $table->string('association', 255)->nullable()->comment('ファイル識別子')->after('comment');
            });
            Schema::table('t_supports', function (Blueprint $table) {
                $table->string('association', 255)->nullable()->comment('ファイル識別子')->after('image_name');
            });
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
