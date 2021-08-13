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
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_customer_estimated_ranks')->insert([
                'name' => $names[$i][0],
                'abbreviation' => $names[$i][1],
                'text_color' => '#ff0000', // カラーコード7桁
                'background_color' => '#4169e1', // カラーコード7桁
            ]);
        }
    }
}
