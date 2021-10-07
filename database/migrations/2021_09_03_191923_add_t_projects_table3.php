<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTProjectsTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_projects', function (Blueprint $table) {
            $table->text('failure_remarks')->nullable()->comment('失注備考')->change();
            $table->text('cancel_reason')->nullable()->comment('キャンセル理由')->change();
            $table->text('remarks')->nullable()->comment('受注備考')->change();
            $table->date('construction_execution_date')->nullable()->comment('着工式実施日');
            $table->date('completion_execution_date')->nullable()->comment('完工式実施日');
            $table->tinyInteger('is_valid')->default(1)->comment('有効フラグ');
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
