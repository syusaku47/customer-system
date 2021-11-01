<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MSupportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['対応履歴1', '対応履歴2', '対応履歴3', '対応履歴4', '対応履歴5'];
        foreach ($names as $i => $name) {
//            company_id = 1 バージョン
            DB::table('m_supports')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'supported' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }
        foreach ($names as $i => $name) {

//            company_id = 2 バージョン
            DB::table('m_supports')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'supported' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }
    }
}
