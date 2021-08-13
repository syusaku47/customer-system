<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TFamiliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * ご家族データ
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_families')->insert([
                'customer_id' => 1,
                'name' => '鈴木三郎',
                'relationship' => '父',
                'mobile_phone' => '090-1111-222' . $val,
                'birth_date' => '1990/01/0' . $val,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
