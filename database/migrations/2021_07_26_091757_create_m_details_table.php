<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_details', function (Blueprint $table) {
            $table->increments('id')->comment('明細マスタID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->string('shohin_cd', 15)->comment('商品CD');
            $table->integer('shohin_kubun')->comment('商品区分');
            $table->integer('daibunrui_id')->comment('大分類');
            $table->integer('tyubunrui_id')->comment('中分類');
            $table->string('name', 255)->comment('名称');
            $table->string('kikaku', 255)->nullable()->comment('規格');
            $table->decimal('suryou',12,2)->nullable()->default(0.0)->comment('数量');
            $table->integer('tani_id')->nullable()->default(0)->comment('単位');
            $table->decimal('genka',12, 2)->nullable()->default(0.0)->comment('原価');
            $table->decimal('shikiri_kakaku',12, 2)->nullable()->default(0.0)->comment('見積単価');
            $table->decimal('shohizeigaku',12, 2)->nullable()->default(0.0)->comment('消費税額');
            $table->integer('shiiresaki_id')->nullable()->default(0)->comment('仕入先id');
            $table->tinyInteger('valid_flag')->default(1)->nullable()->comment('有効フラグ');
            $table->string('filename', 255)->nullable()->comment('ファイル名');
            $table->string('server_filename', 255)->nullable()->comment('サーバファイル名');
            $table->string('extension', 4)->nullable()->comment('形式');
            $table->integer('order')->comment('表示順');
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
        Schema::dropIfExists('m_details');
    }
}
