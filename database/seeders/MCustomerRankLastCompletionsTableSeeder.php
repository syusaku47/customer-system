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
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_customer_rank_last_completions')->insert([
                'name' => $names[$i][0],
                'abbreviation' => $names[$i][1],
                'date' => $i + 1, // 日数
            ]);
        }
    }
}
