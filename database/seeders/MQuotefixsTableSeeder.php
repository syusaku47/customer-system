<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MQuotefixsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ( range(1,5) as $i){
            DB::table('m_quotefixs')->insert([
                'item' => "項目".$i,
                'name' => "名称".$i,
                'estimate' => mt_rand() / mt_getrandmax() * 5,//0から5までの小数乱数,
                'cost' => mt_rand() / mt_getrandmax() * 5,//0から5までの小数乱数,
            ]);
        }
    }
}
