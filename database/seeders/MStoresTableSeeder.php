<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MStoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 店舗マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['鎌倉', '金沢文庫', 'テスト'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_stores')->insert([
                'name' => $names[$i],
                'short_name' => $names[$i],
                'furigana' => 'テンポ' . ($i + 1),
                'tel_no' => '080-1111-2221',
                'fax_no' => '080-1111-2224',
                'post_no' => '1540005',
                'prefecture' => '東京',
                'city' => '台東区',
                'address' => '浅草橋5-5-5',
                'building_name' => 'キムラビル4F',
                'is_valid' => 1, // 0:無効 1:有効
                'free_dial' => '080-1111-2225',
                'bank_name' => '三井住友銀行',
                'bank_account_no' => '1234567',
                'holder' => '山田太郎',
                'bank_account' => rand(0, 1), // 1:普通口座 2:当座
                "logo" => Str::random(100),
            ]);
        }
    }
}
