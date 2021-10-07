<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_signatures', function (Blueprint $table) {
            $table->integer('company_id')->comment('契約会社マスタID')->unsigned();
            $table->foreign('company_id')->references('id')->on('m_contract_companies');
        });
        Schema::table('m_quotefixs', function (Blueprint $table) {
            $table->integer('company_id')->comment('契約会社マスタID')->unsigned();
            $table->foreign('company_id')->references('id')->on('m_contract_companies');
        });
        Schema::table('m_parts', function (Blueprint $table) {
            $table->integer('company_id')->comment('契約会社マスタID')->unsigned();
            $table->foreign('company_id')->references('id')->on('m_contract_companies');
        });

//        Schema::table('t_customers', function (Blueprint $table) {
//            // unsigned付与
//            $table->integer('sales_shop')->nullable()->comment('営業担当（店舗）')->unsigned()->change();
//            $table->integer('sales_contact')->comment('営業担当（担当者）')->unsigned()->change();
//            $table->integer('estimated_rank')->nullable()->comment('顧客見込みランク')->unsigned()->change();
//            $table->integer('source_id')->nullable()->comment('発生源')->unsigned()->change();
//            $table->integer('area_id')->nullable()->comment('エリア')->unsigned()->change();
//            $table->integer('building_category_id')->nullable()->comment('建物分類')->unsigned()->change();
//            $table->integer('madori_id')->nullable()->comment('間取り')->unsigned()->change();
//            // 外部キー制約
//            $table->foreign('sales_shop')->references('id')->on('m_stores'); // 店舗マスタ
//            $table->foreign('sales_contact')->references('id')->on('m_employees'); // 社員マスタ
//            $table->foreign('estimated_rank')->references('id')->on('m_customer_estimated_ranks'); // 顧客見込みランクマスタ
//            $table->foreign('source_id')->references('id')->on('m_sources'); // 発生源マスタ
//            $table->foreign('area_id')->references('id')->on('m_areas'); // エリアマスタ
//            $table->foreign('building_category_id')->references('id')->on('m_buildings'); // 建物分類マスタ
//            $table->foreign('madori_id')->references('id')->on('m_madoris'); // 間取りマスタ
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
