<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTSupportsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 対応履歴データ
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_supports', function (Blueprint $table) {
            $table->renameColumn('image', 'image_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_supports', function (Blueprint $table) {
            $table->renameColumn('image_name', 'image');
        });
    }
}
