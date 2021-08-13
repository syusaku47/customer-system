<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 受注データ
     *
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_orders')->insert([
                'project_id' => $val,
                'quote_id' => $val,
//                'company_id' => , // 後で追加
                'contract_date' => '2021/07/0' . $val,
                'construction_start_date' => '2021/08/0' . $val,
                'completion_end_date' => '2021/09/0' . $val,
                'groundbreaking_ceremony' => '2021/10/0' . $val,
                'completion_based' => '2021/11/0' . $val,
                'contract_money' => 10000 . $val,
                'contract_billing_date' => '2021/11/0' . $val,
                'contract_expected_date' => '2021/12/0' . $val,
                'start_construction_money' => 20000 . $val,
                'start_construction_billing_date' => '2022/01/0' . $val,
                'start_construction_expected_date' => '2022/02/0' . $val,
                'intermediate_gold1' => 30000 . $val,
                'intermediate1_billing_date' => '2022/03/0' . $val,
                'intermediate1_expected_date' => '2022/04/0' . $val,
                'intermediate_gold2' => 40000 . $val,
                'intermediate2_billing_date' => '2022/05/0' . $val,
                'intermediate2_expected_date' => '2022/06/0' . $val,
                'completion_money' => 50000 . $val,
                'completion_billing_date' => '2022/07/0' . $val,
                'completion_expected_date' => '2022/08/0' . $val,
                'unallocated_money' => 60000 . $val,
                'remarks' => '特になし',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'last_updated_by' => '山田太郎' . $val,
            ]);
        }
    }
}
