<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MMyCarTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * マイカー種別マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['軽自動車', 'ミニバン', 'セダン', 'ステーションワゴン', 'オープン', 'ハイブリッド', '福祉車両', 'クーペ', 'RV', '輸入車', 'バイク', '無し', 'その他'];
        foreach ($names as $i => $name) {
//            company_id = 1 バージョン
            DB::table('m_my_car_types')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'name' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 99),
            ]);
        }

        foreach ($names as $i => $name) {
//            company_id = 2 バージョン
            DB::table('m_my_car_types')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'name' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 99),
            ]);
        }
    }
}
