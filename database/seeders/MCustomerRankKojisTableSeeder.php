<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MCustomerRankKojisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 顧客ランク（工事金額）マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = [['A', 'A'], ['B', 'B'], ['C', 'C'], ['D', 'D']];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_customer_rank_kojis')->insert([
                'name' => $names[$i][0],
                'abbreviation' => $names[$i][1],
                'text_color' => '#ff0000', // カラーコード7桁
                'background_color' => '#4169e1', // カラーコード7桁
                'amount' => 100000,
            ]);
        }
    }
}
