<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE m_after_maintenances COMMENT 'アフターメンテナンスマスタ'");
        DB::statement("ALTER TABLE m_areas COMMENT 'エリアマスタ'");
        DB::statement("ALTER TABLE m_buildings COMMENT '建物分類マスタ'");
        DB::statement("ALTER TABLE m_categories COMMENT '大分類マスタ'");
        DB::statement("ALTER TABLE m_credits COMMENT '単位マスタ'");
        DB::statement("ALTER TABLE m_customer_estimated_ranks COMMENT '顧客見込みランクマスタ'");
        DB::statement("ALTER TABLE m_customer_rank_kojis COMMENT '顧客ランク（工事金額）マスタ'");
        DB::statement("ALTER TABLE m_customer_rank_last_completions COMMENT '顧客ランク（最終完工日）マスタ'");
        DB::statement("ALTER TABLE m_details COMMENT '明細マスタ'");
        DB::statement("ALTER TABLE m_employees COMMENT '社員マスタ'");
        DB::statement("ALTER TABLE m_field_confirm_items COMMENT '現場準備確認項目マスタ'");
        DB::statement("ALTER TABLE m_inquiries COMMENT '問合せマスタ'");
        DB::statement("ALTER TABLE m_koji_confirm_items COMMENT '工事確認項目マスタ'");
        DB::statement("ALTER TABLE m_lostorders COMMENT '失注理由マスタ'");
        DB::statement("ALTER TABLE m_madoris COMMENT '間取りマスタ'");
        DB::statement("ALTER TABLE m_my_car_types COMMENT 'マイカー種別マスタ'");
        DB::statement("ALTER TABLE m_parts COMMENT '部位マスタ'");
        DB::statement("ALTER TABLE m_project_ranks COMMENT '案件ランクマスタ'");
        DB::statement("ALTER TABLE m_quotefixs COMMENT '見積定型マスタ'");
        DB::statement("ALTER TABLE m_signatures COMMENT '署名マスタ'");
        DB::statement("ALTER TABLE m_sources COMMENT '発生源マスタ'");
        DB::statement("ALTER TABLE m_stores COMMENT '店舗マスタ'");
        DB::statement("ALTER TABLE m_sub_categories COMMENT '中分類マスタ'");
        DB::statement("ALTER TABLE m_supports COMMENT '対応履歴マスタ'");
        DB::statement("ALTER TABLE m_tags COMMENT '関連タグマスタ'");
        DB::statement("ALTER TABLE m_taxes COMMENT '消費税マスタ'");
        DB::statement("ALTER TABLE t_customers COMMENT '顧客データ'");
        DB::statement("ALTER TABLE t_families COMMENT '家族データ'");
        DB::statement("ALTER TABLE t_files COMMENT 'ファイルデータ'");
        DB::statement("ALTER TABLE t_maintenances COMMENT 'メンテナンスデータ'");
        DB::statement("ALTER TABLE t_orders COMMENT '受注データ'");
        DB::statement("ALTER TABLE t_pets COMMENT 'ペットデータ'");
        DB::statement("ALTER TABLE t_projects COMMENT '案件データ'");
        DB::statement("ALTER TABLE t_quote_details COMMENT '見積明細データ'");
        DB::statement("ALTER TABLE t_quotes COMMENT '見積データ'");
        DB::statement("ALTER TABLE t_supports COMMENT '対応履歴データ'");
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
