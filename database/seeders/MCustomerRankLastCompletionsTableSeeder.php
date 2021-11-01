<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MCustomerRankLastCompletionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 顧客ランク（最終完工日）マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = [['1', '1'], ['2', '2'], ['3', '3'], ['4', '4']];
        foreach ($names as $i => $name) {
//            company_id = 1 バージョン
            DB::table('m_customer_rank_last_completions')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'name' => $names[$i][0],
                'abbreviation' => $names[$i][1],
                'date' => $i + 1, // 日数
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 999),
            ]);
        }
        foreach ($names as $i => $name) {
//            company_id = 2 バージョン
            DB::table('m_customer_rank_last_completions')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'name' => $names[$i][0],
                'abbreviation' => $names[$i][1],
                'date' => $i + 1, // 日数s
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 999),
            ]);
        }

    }
}
