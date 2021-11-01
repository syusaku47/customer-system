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
                'company_id' => 1,
                'internal_id' => $i + 1,
                'shohin_cd' => $i,
                'shohin_kubun' => rand(1,5),
                'daibunrui_id' => $i,
                'tyubunrui_id' => $i,
                'name' => '商品'.$i,
                'kikaku' => "規格".$i,
                'suryou' => rand(1,5),
                'tani_id' => rand(1,5),
                'genka' => rand(10000,20000),
                'shikiri_kakaku' => rand(10000,20000),
                'valid_flag' => 1, // 0:無効 1:有効
                'order' => rand(1,100),
            ]);
        }
        foreach (range(1,5) as $i){
            DB::table('m_details')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'shohin_cd' => $i,
                'shohin_kubun' => rand(1,5),
                'daibunrui_id' => $i,
                'tyubunrui_id' => $i,
                'name' => '商品'.$i,
                'kikaku' => "規格".$i,
                'suryou' => rand(1,5),
                'tani_id' => rand(1,5),
                'genka' => rand(10000,20000),
                'shikiri_kakaku' => rand(10000,20000),
                'valid_flag' => 1, // 0:無効 1:有効
                'order' => rand(1,100),
            ]);
        }
    }
}
