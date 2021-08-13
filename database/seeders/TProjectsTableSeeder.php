<?php

namespace Database\Seeders;

use App\Models\ModelBase;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 案件データ
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_projects')->insert([
                'customer_id' => $val,
                'field_name' => '山田太郎様',
                'name' => '山田太郎様宅新築',
                'field_address' => '千住宮元町２７－６',
                'field_tel_no' => '03-1234-567' . $val,
                'field_fax_no' => '03-1234-567' . ($val + 1),
                'construction_period_start' => '2021/01/2' . $val,
                'construction_period_end' => '2021/05/2' . $val,
                'construction_start_date' => '2021/01/2' . $val,
                'construction_date' => '2021/05/2' . $val,
                'completion_end_date' => '2021/05/2' . $val,
                'completion_date' => '2022/05/2' . $val,
                'source_id' => $val, // 発生源マスタのID
                'contract_no' => 'C00000305' . $val,
                'contract_date' => '2021/01/0' . $val,
                'cancel_date' => '2021/01/0' . ($val + 1),
                'expected_amount' => 100000 + intval($val),
                'order_price' => 200000 + intval($val),
                'project_rank' => $val, // 案件ランクマスタのID
                'project_store' => $val, // 店舗マスタのID
                'project_representative' => $val, // 社員マスタのID
                'post_no' => '1200043',
                'prefecture' => ModelBase::PREFECTURE[$val],
                'city' => '足立区',
                'address' => '千住宮元町２７－６',
                'building_name' => '木村ビル４F',
                'construction_parts' => $val . ' ' . ($val + 1) . ' ' . ($val + 2), // 部位マスタのID を半角スペース区切りで結合し格納
                'complete_date' => '2019/06/0' . $val,
                'failure_date' => '2019/07/0' . $val,
                'failure_cause' => 1, // 失注理由マスタのID
                'failure_remarks' => '特になし',
                'cancel_reason' => 'キャンセル理由',
                'execution_end' => (bool)rand(0, 1),
                'order_detail1' => 45000 + intval($val),
                'order_detail2' => 54000 + intval($val),
                'construction_status' => '案件化',
                'complete_flag' => (bool)rand(0, 1),
                'alert_flag' => (bool)rand(0, 1),
                'remarks' => '特になし',
                'lat' => $val . '5.6999478238869',
                'lng' => $val . '39.780773498344',
                'is_editing' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'last_updated_by' => '山田太郎',
            ]);
        }
    }
}
