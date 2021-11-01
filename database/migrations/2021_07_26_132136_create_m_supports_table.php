<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *対応履歴マスタ
     * @return void
     */
    public function up()
    {
        Schema::create('m_supports', function (Blueprint $table) {
            $table->increments('id')->comment('対応履歴マスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->string('supported', 255)->nullable()->comment('対応履歴情報');
            $table->tinyInteger('is_valid')->default(1)->nullable()->comment('有効フラグ');
            $table->integer('order')->default(999)->comment('表示順');
            $table->unique(['company_id', 'internal_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_supports');
    }
}
