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
        foreach (range(1,4) as $i){

            DB::table('m_sub_categories')->insert([
                'category_id' => $i,
                'name' => "中分類".$i,
                'is_valid' => 1, // 0:無効 1:有効
            ]);
        }
    }
}
