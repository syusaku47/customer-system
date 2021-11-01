<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MLostordersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 失注理由マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['価格', '他社競合', '予算', '納期', '画面から新規', '無効データ'];
        foreach ($names as $i => $name) {
//            company_id = 1 バージョン
            DB::table('m_lostorders')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'lost_reason' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }

        foreach ($names as $i => $name) {
//            company_id = 2 バージョン
            DB::table('m_lostorders')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'lost_reason' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }
    }
}
