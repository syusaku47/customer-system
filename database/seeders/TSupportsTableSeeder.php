<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TSupportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 対応履歴データ
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_supports')->insert([
                'customer_id' => 1,
                'project_id' => $val,
//                'company_id' => $val, // 後で追加
                'reception_date' => '2021/04/2' . $val,
                'supported_kubun' => 0, // 0:未対応 1:全て 2:対応済
                'supported_content' => '対応内容' . $val,
                'detail' => '対応詳細' . $val,
                'supported_date' => '2021/05/2' . $val,
                'category' => $val,
                'supported_responsible_store' => $val,
                'supported_representative' => $val,
                'reception_time' => Carbon::now(),
                'supported_history_name' => '対応履歴名' . $val,
                'supported_person' => $val,
                'supported_complete_date' => '2021/06/2' . $val,
                'is_fixed' => 0, // 0:未対応 1:全て 2:対応済
                'media' => $val,
                'image_name' => '画像' . $val,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'last_updated_by' => '山田太郎' . $val,
            ]);
        }
    }
}
