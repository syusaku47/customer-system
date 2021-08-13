<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MTaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataTime = new Carbon("2020-07-21");

        foreach ( range(1,9) as $val ){
            DB::table('m_taxes')->insert([
                'start_date' => $dataTime->addDays($val),//現在の時間
                'tax_rate' => mt_rand() / mt_getrandmax() * 5,//0から5までの小数乱数
                'is_valid' => rand( 0,1 ), // 0:無効 1:有効
            ]);
        }

    }
}
