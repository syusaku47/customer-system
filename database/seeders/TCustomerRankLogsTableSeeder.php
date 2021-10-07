<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TCustomerRankLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 顧客ランク自動更新ログデータ
     *
     * @return void
     */
    public function run()
    {
        $before = ["A1", "A2", "A3"];
        $after = ["B1", "B2", "B3"];
        foreach (range(1, 3) as $val) {
            DB::table('t_customer_rank_logs')->insert([
                'id' => $val,
                'customer_id' => 1,
                'customer_name' => '山田太郎' . $val,
                'sales_contact' => $val,
                'customer_rank_before_change' =>  $before[$val - 1],
                'customer_rank_after_change' => $after[$val - 1],
                'total_work_price' => 100000 . $val,
                'total_work_times' => $val,
                'last_completion_date' => '2021/07/0' . $val,
                'updated_date' => '2021/08/0' . $val,
            ]);
        }
    }
}
