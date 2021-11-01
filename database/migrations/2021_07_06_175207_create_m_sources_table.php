<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSourcesTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 対応履歴 媒体(発生源)マスタ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sources', function (Blueprint $table) {
            $table->increments('id')->comment('対応履歴 媒体(発生源)マスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->string('name', 255)->nullable()->comment('名称');
            $table->tinyInteger('is_valid')->default(1)->nullable()->comment('有効フラグ');
            $table->integer('order')->default(999)->comment('表示順');
            $table->unique(['company_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_sources');
    }
}
