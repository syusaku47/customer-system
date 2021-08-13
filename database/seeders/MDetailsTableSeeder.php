<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1,5) as $i){

            DB::table('m_details')->insert([
                'product_kubun' => $i,
                'category_name' => $i,
                'subcategory_name' => $i,
                'name' => $i,
                'standard' => "規格".$i,
                'quantity' => rand(1,5),
                'credit_name' => "kg",
                'quote_unit_price' => rand(10000,20000),
                'prime_cost' => rand(10000,20000),
                'is_valid' => 1, // 0:無効 1:有効
            ]);
        }
    }
}
