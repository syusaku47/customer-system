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
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_supports')->insert([
                'supported' => $names[$i],
                'is_valid' => rand(0,1), // 0:無効 1:有効
            ]);
        }
    }
}
