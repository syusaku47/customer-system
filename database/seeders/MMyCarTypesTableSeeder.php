<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MMyCarTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * マイカー種別マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['軽自動車', 'ミニバン', 'セダン', 'ステーションワゴン', 'オープン', 'ハイブリッド', '福祉車両', 'クーペ', 'RV', '輸入車', 'バイク', '無し', 'その他'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_my_car_types')->insert([
                'name' => $names[$i],
                'is_input' => rand(0, 1), // 1:入力有 0:入力無
                'is_valid' => 1, // 0:無効 1:有効
            ]);
        }
    }
}
