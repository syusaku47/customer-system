<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TQuoteDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 見積明細データ
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_quote_details')->insert([
                'quote_id' => $val,
                'category_id' => $val,
                'sub_category_id' => $val,
                'item_kubun' => $val,
                'category_percent' => 1.2 . $val,
                'sub_category_percent' => 10.2 . $val,
                'koji_component_name' => '養生メッシュシート施工',
                'print_name' => '印刷',
                'standard' => '規格外',
                'quantity' => '300',
                'unit' => $val,
                'quote_unit_price' => 60 . $val,
                'price' => 70 . $val,
                'prime_cost' => 80 . $val,
                'cost_amount' => 90 . $val,
                'gross_profit_amount' => 100 . $val,
                'gross_profit_rate' => 110 . $val,
                'remarks' => '特になし',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'last_updated_by' => '山田太郎',
            ]);
        }
    }
}
