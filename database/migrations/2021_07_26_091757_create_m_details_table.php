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
            $table->string('product_kubun', 30)->nullable()->comment('商品区分');
            $table->string('category_name', 30)->nullable()->comment('大分類名称');
            $table->string('subcategory_name', 30)->nullable()->comment('中分類名称');
            $table->string('name', 30)->nullable()->comment('名称');
            $table->string('standard', 30)->nullable()->comment('規格');
            $table->integer('quantity')->nullable()->comment('数量');
            $table->string('credit_name', 30)->nullable()->comment('単位名称');
            $table->decimal('quote_unit_price',20, 2)->nullable()->comment('見積単価');
            $table->decimal('prime_cost',20, 2)->nullable()->comment('原価');
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
        Schema::dropIfExists('m_details');
    }
}
