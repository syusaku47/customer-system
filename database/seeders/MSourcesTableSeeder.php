<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MSourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 発生源マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['ホームページ', 'チラシ', 'ＤＭ', 'ポスティング', 'イベント', '訪問営業', '株主総会', 'モデルルーム展示会'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_sources')->insert([
                'name' => $names[$i],
                'is_valid' => 1, // 0:無効 1:有効
            ]);
        }
    }
}
