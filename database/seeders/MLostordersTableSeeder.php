<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MLostordersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 失注理由マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['価格', '他社競合', '予算', '納期', '画面から新規', '無効データ'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_lostorders')->insert([
                'lost_reason' => '失注理由' . $names[$i],
                'is_valid' => 1, // 0:無効 1:有効
            ]);
        }
    }
}
