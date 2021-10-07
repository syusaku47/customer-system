<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TQuotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 見積データ
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_quotes')->insert([
                'project_id' => $val,
//                'company_id' => $val, // 後で追加
                'order_flag' => rand(0, 1),
                'quote_no' => 'E00000000' . $val,
                'quote_date' => '2021/01/2' . $val,
                'quote_creator' => $val,
                'quote_price' => 100000 + intval($val),
                'tax_amount_quote' => 200000 + intval($val),
                'including_tax_total_quote' => 300000 + intval($val),
                'cost_sum' => 400000 + intval($val),
                'tax_amount_cost' => 500000 + intval($val),
                'including_tax_total_cost' => 600000 + intval($val),
                'adjustment_amount' => 700000 + intval($val),
                'quote_expiration_date' => '2021/02/2' . $val,
                'order_expected_date' => '2021/03/2' . $val,
                'remarks' => '特になし',
                'meisai' => '見積明細',
                'field_cooperating_cost_estimate' => 800000 + intval($val),
                'field_cooperating_cost' => 900000 + intval($val),
                'call_cost_estimate' => 1000000 + intval($val),
                'call_cost' => 1100000 + intval($val),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'last_updated_by' => '山田太郎',
            ]);
        }
    }
}
