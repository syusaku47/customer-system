<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TMaintenancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * メンテナンスデータ
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_maintenances')->insert([
                'customer_id' => 1,
                'project_id' => $val,
//                'company_id' => $val,
                'maintenance_date' => '2021/01/2' . $val,
                'supported_kubun' => 0, // 0:未対応 1:全て 2:対応済
                'title' => 'タイトル' . $val,
                'supported_date' => '2021/05/2' . $val,
                'supported_content' => '対応内容' . $val,
                'detail' => '対応詳細' . $val,
                'is_valid' => 1,
                'lat' => $val . '5.6999478238869',
                'lng' => $val . '39.780773498344',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'last_updated_by' => '山田太郎',
            ]);
        }
    }
}
