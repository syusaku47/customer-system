<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTQuoteDetailsTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 見積明細データ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_quote_details', function (Blueprint $table) {
            $table->increments('id')->comment('見積明細ID');
            $table->integer('quote_id')->comment('見積ID')->unsigned();
//            $table->integer('category_id')->nullable()->comment('大分類ID')->unsigned(); // TODO 後で追加
//            $table->integer('sub_category_id')->nullable()->comment('中分類ID')->unsigned(); // TODO 後で追加
//            $table->integer('item_kubun')->nullable()->comment('商品区分')->unsigned(); // TODO 後で追加
            $table->integer('category_id')->nullable()->comment('大分類ID');
            $table->integer('sub_category_id')->nullable()->comment('中分類ID');
            $table->integer('item_kubun')->nullable()->comment('商品区分');
            $table->float('category_percent')->nullable()->comment('大分類パーセント');
            $table->float('sub_category_percent')->nullable()->comment('中分類パーセント');
            $table->string('koji_component_name', 255)->nullable()->comment('工事・部材名称');
            $table->string('print_name', 50)->nullable()->comment('印刷名称');
            $table->string('standard', 50)->nullable()->comment('規格');
            $table->float('quantity')->nullable()->comment('数量');
            $table->string('unit', 3)->nullable()->comment('単位');
            $table->decimal('quote_unit_price', 20, 2)->nullable()->comment('見積単価');
            $table->decimal('price', 20, 2)->nullable()->comment('金額');
            $table->decimal('prime_cost', 20, 2)->nullable()->comment('原価');
            $table->decimal('cost_amount', 20, 2)->nullable()->comment('原価金額');
            $table->decimal('gross_profit_amount', 20, 2)->nullable()->comment('粗利金額');
            $table->float('gross_profit_rate')->nullable()->comment('粗利率');
            $table->string('remarks', 500)->nullable()->comment('備考');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->string('last_updated_by')->comment('最終更新者');
            // 外部キー成約
            $table->foreign('quote_id')->references('id')->on('t_quotes');
//            $table->foreign('category_id')->references('id')->on('m_categories'); // TODO 後で追加
//            $table->foreign('sub_category_id')->references('id')->on('m_subcategories'); // TODO 後で追加
//            $table->foreign('item_kubun')->references('id')->on('m_details'); // TODO 後で追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_quote_details');
    }
}
