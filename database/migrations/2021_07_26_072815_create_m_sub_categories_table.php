<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sub_categories', function (Blueprint $table) {
            $table->increments('id')->comment('中分類マスタID');
            $table->string('category_name', 20)->nullable()->comment('大分類名称');
            $table->string('name', 20)->nullable()->comment('中分類名称');
            $table->tinyInteger('is_valid')->default(1)->nullable()->comment('有効フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_sub_categories');
    }
}
