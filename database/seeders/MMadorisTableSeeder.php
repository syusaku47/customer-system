<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MMadorisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 間取りマスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['1R', '1K', '1DK', '1LDK', '2K', '2DK', '2LDK', '3K', '3DK', '3LDK', '4K', '4DK', '4LDK', '5K', '5DK', '5LDK', '6K', '6DK', '6LDK', '7k'];
        foreach ($names as $i => $name) {
//            company_id = 1 バージョン
            DB::table('m_madoris')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'name' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }

        foreach ($names as $i => $name) {
//            company_id = 2 バージョン
            DB::table('m_madoris')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'name' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }
    }
}
