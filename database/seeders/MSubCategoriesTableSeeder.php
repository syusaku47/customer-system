<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MSubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        company_id =1のseeder
        $names = ['中分類1', '中分類2', '中分類3', '中分類4', '中分類5'];
        for($i = 1; $i <= count($names) - 1; $i++) {
            DB::table('m_sub_categories')->insert([
                'internal_id' => $i + 1,
                'company_id' => 1,
                'category_id' => $i,
                'name' => $names[$i],
                'order' => $i,
                'is_valid' => rand(0,1), // 0:無効 1:有効
            ]);
        }

        //        company_id =2のseeder
        $names = ['中分類1', '中分類2', '中分類3', '中分類4', '中分類5'];
        for($i = 1; $i <= count($names) - 1; $i++) {
            DB::table('m_sub_categories')->insert([
                'internal_id' => $i + 1,
                'company_id' => 2,
                'category_id' => $i,
                'name' => $names[$i],
                'order' => $i,
                'is_valid' => rand(0,1), // 0:無効 1:有効
            ]);
        }
    }
}
