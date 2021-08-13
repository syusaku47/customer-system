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
            DB::table('m_credits')->insert([
                'name' => $names[$i],
                'is_valid' => rand(0,1), // 0:無効 1:有効
            ]);
        }
    }
}
