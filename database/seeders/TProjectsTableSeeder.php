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
        $names = ['案件1', '案件2', '案件3'];

        foreach ($names as $key => $name) {
            DB::table('t_projects')->insert([
                'internal_id' => $key + 1,
                'company_id' => 1,
                'customer_id' => $key + 1,
                'field_name' => '山田太郎様',
                'name' => $name . '様宅新築',
                'post_no' => '154000' . $key,
                'jisx0401_code' => '1' . $key,
                'jisx0402_code' => '4000' . $key,
                'prefecture' => $key + 1,
                'city' => '台東区',
                'address' => '浅草橋5-5-5',
                'building_name' => 'キムラビル4F',

//                'field_address' => '千住宮元町２７－６',
                'field_tel_no' => '03-1234-567' . $key,
                'field_fax_no' => '03-1234-567' . ($key + 1),
                'employee_cd' => 'user' . ($key + 1),

                'construction_period_start' => '2021-01-2' . ($key + 1),
                'construction_period_end' => '2021-05-2' . ($key + 1),
                'construction_start_date' => '2021-01-2' . ($key + 1),
                'construction_date' => '2021-05-2' . ($key + 1),
                'completion_end_date' => '2021-05-2' . ($key + 1),
                'completion_date' => '2022-05-2' . ($key + 1),
                'source_id' => $key + 1, // 発生源マスタのID
                'contract_no' => 'C00000305' . $key,
                'contract_date' => '2021-01-0' . ($key + 1),
                'failure_date' => null,

                'mitumori_kin' => 100000 + rand(0, 10000),
                'order_price' => 100000,
                'jyutyu_kin_1' => 200000,
                'jyutyu_kin_2' => 300000,
                'jyutyu_kin_all' => 600000, //受注金額+受注金額1+受注金額2
                'jyutyu_kin_first' => 600000,
                'jyutyu_genka' => 100000 + rand(0, 10000),
                'hattyu_genka' => 100000 + rand(0, 10000),
                'saisyu_genka' => 100000 + rand(0, 10000),
                'jyutyu_sorieki' => 100000 + rand(0, 10000),
                'saisyu_sorieki' => 100000 + rand(0, 10000),
                'kakutei_genka' => 100000 + rand(0, 10000),

                'execution_end' => rand(0, 1),
                'project_rank' => $key + 1, // 案件ランクマスタのID
                'construction_execution_date' => '2021-01-0' . ($key + 1),
                'completion_execution_date' => '2021-01-0' . ($key + 1),
                'enquete_send_dt' => '2021-01-0' . ($key + 1),
                'enquete_dt' => '2021-01-0' . ($key + 1),
                'complete_date' => '2019-06-0' . ($key + 1),
                'saisyu_meisai' => '2019-06-0' . ($key + 1),
                'remarks' => '特になし',
                'failure_remarks' => '特になし',
                'entry_dt' => '2021-01-0' . ($key + 1),
                'datetime_upd' => date('Y-m-d'),
                'last_updated_by' => '山田太郎',
                'valid_flag' => rand(0, 1), // 0:無効 1:有効
                'enquete_point' => $key + 1,
                'failure_cause' => 1, // 失注理由マスタのID
                'billing_remarks' => 1, // 失注理由マスタのID
                'cancel_date' => '2021-01-0' . ($key + 1),
                'cancel_reason' => 'キャンセル理由',
                'employee_id' => $key + 1,
                'store_id' => $key + 1,  // 店舗マスタのID
                'construction_parts' => $key . ' ' . ($key + 1) . ' ' . ($key + 2), // 部位マスタのID を半角スペース区切りで結合し格納
                'lat' => $key . '5.6999478238869',
                'lng' => $key . '39.780773498344',


//                'expected_amount' => 100000 + intval($key),
//                'order_price' => 200000 + intval($key),
//                'project_store' => $key, // 店舗マスタのID
//                'project_representative' => $key, // 社員マスタのID
//                'order_detail1' => 45000 + intval($key),
//                'order_detail2' => 54000 + intval($key),
//                'construction_status' => '案件化',
//                'complete_flag' => (bool)rand(0, 1),
//                'alert_flag' => (bool)rand(0, 1),
//                'is_editing' => 0,
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
