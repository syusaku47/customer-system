<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MCustomerEstimatedRanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 顧客見込みランクマスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = [['プラチナ', 'S'], ['ゴールド', 'S'], ['シルバー', 'A'], ['ブロンズ', 'B'], ['C', 'C'], ['D', 'D'], ['E', 'E'], ['F', 'F']];
        foreach ($names as $i => $name) {
//            company_id = 1 バージョン
            DB::table('m_customer_estimated_ranks')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'name' => $names[$i][0],
                'abbreviation' => $names[$i][1],
                'text_color' => '#ff000' . $i, // カラーコード7桁
                'background_color' => '#4169e' . $i, // カラーコード7桁
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 999),
            ]);
        }
        foreach ($names as $i => $name) {

//            company_id = 2 バージョン
            DB::table('m_customer_estimated_ranks')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'name' => $names[$i][0],
                'abbreviation' => $names[$i][1],
                'text_color' => '#ff000' . $i, // カラーコード7桁
                'background_color' => '#4169e' . $i, // カラーコード7桁
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 999),
            ]);
        }
    }
}
