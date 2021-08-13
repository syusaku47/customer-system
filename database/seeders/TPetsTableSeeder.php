<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TPetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * ペットデータ
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_pets')->insert([
                'customer_id' => $val,
                'name' => 'ポチ',
                'type' => '犬',
                'sex' => $val, // 1:指定なし 2:オス 3:メス
                'age' => $val,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
