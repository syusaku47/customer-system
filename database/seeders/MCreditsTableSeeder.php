<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MCreditsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['km', 'm', 'cm', 'mm'];
        for($i = 0; $i <= count($names) - 1; $i++) {
//            company_id = 1 バージョン
            DB::table('m_credits')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'name' => $names[$i],
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }

        for($i = 0; $i <= count($names) - 1; $i++) {
//            company_id = 2 バージョン
            DB::table('m_credits')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'name' => $names[$i],
                'is_valid' => rand(0,1), // 0:無効 1:有効
                'order' => rand(1,100),
            ]);
        }

    }
}
