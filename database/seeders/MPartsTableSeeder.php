<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MPartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 部位マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['キッチン', 'トイレ', '浴室', '無効部位'];
        foreach ($names as $i => $name) {
//            company_id = 1 バージョン
            DB::table('m_parts')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'name' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 99),
            ]);
        }

        foreach ($names as $i => $name) {
//            company_id = 2 バージョン
            DB::table('m_parts')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'name' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 99),
            ]);
        }
    }
}
