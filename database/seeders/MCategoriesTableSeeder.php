<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        company_id =1のseeder
        $names = ['大分類1', '大分類2', '大分類3', '大分類4', '大分類5'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_categories')->insert([
                'internal_id' => $i + 1,
                'company_id' => 1,
                'name' => $names[$i],
                'order' => $i,
                'is_valid' => rand(0,1), // 0:無効 1:有効
            ]);
        }

//        company_id =2のseeder
        $names = ['大分類1', '大分類2', '大分類3', '大分類4', '大分類5'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_categories')->insert([
                'internal_id' => $i + 1,
                'company_id' => 2,
                'name' => $names[$i],
                'order' => $i,
                'is_valid' => rand(0,1), // 0:無効 1:有効
            ]);
        }
    }
}
