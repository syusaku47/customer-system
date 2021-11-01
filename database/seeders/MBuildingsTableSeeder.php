<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MBuildingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 建物分類マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['平屋', 'マンション', '２階建て', '２世帯平屋', 'アパート'];
        for ($i = 0; $i <= count($names) - 1; $i++) {
//            company_id = 1 バージョン
            DB::table('m_buildings')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'name' => $names[$i],
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),

            ]);
        }

        for ($i = 0; $i <= count($names) - 1; $i++) {
//            company_id = 2 バージョン
            DB::table('m_buildings')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'name' => $names[$i],
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),

            ]);
        }
    }
}
