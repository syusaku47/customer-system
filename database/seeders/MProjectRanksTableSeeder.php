<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MProjectRanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 案件ランクマスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = [['SS', 'SS'], ['S', 'S'], ['A', 'A'], ['B', 'B'], ['C', 'C'], ['D', 'D'], ['E', 'E'], ['F', 'F']];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_project_ranks')->insert([
                'name' => $names[$i][0],
                'abbreviation' => $names[$i][1],
                'text_color' => '#ff0000', // カラーコード7桁
                'background_color' => '#4169e1', // カラーコード7桁
            ]);
        }
    }
}
