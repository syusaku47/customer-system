<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 関連タグマスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['友の会入会済', 'リフォームアルバム有', '事例許可済', '現場見学会許可済'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_tags')->insert([
                'name' => $names[$i],
                'is_input' => rand(0, 1),
                'is_valid' => 1, // 0:無効 1:有効
            ]);
        }
    }
}
