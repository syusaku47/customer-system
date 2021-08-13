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
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_parts')->insert([
                'name' => $names[$i],
                'is_input' => rand(0, 1), // 1:入力有 0:入力無
                'is_valid' => 1, // 0:無効 1:有効
            ]);
        }
    }
}
