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
        for($i = 1; $i <= count($names); $i++) {

            DB::table('m_stores')->insert([
                'company_id' => 1,
                'name' => $names[$i - 1],
                'short_name' => $names[$i - 1],
                'furigana' => 'テンポ' . ($i + 1),
                'tel_no' => '080-1111-222' . $i,
                'fax_no' => '080-1111-222' . ($i + 5),
                'post_no' => '1540005',
                'jisx0401_code' => rand(1, 47),
                'jisx0402_code' => rand(10000, 99999),
                'prefecture' => $i,
                'city' => '台東区' . $i,
                'address' => '浅草橋5-5-' . $i,
                'building_name' => 'キムラビル' . $i . 'F',
                'bank_account' => null,
                'bank_account2' => null,
                'bank_account3' => null,
                'bank_account4' => null,
                'bank_account5' => null,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'free_dial' => '0123-000-00' . $i,
                'holder' => '山田太郎',
                'bank_name' => '三井住友銀行' . $i,
                'bank_store_name' => '金沢文庫' . $i,
                'account' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no' => $i . '234567' . $i,
                'bank_name2' => '三井住友銀行' . $i,
                'bank_store_name2' => '金沢文庫' . $i,
                'account2' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no2' => $i . '234567',
                'bank_name3' => '三井住友銀行' . $i,
                'bank_store_name3' => '金沢文庫' . $i,
                'account3' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no3' => $i . '234567',
                'bank_name4' => '三井住友銀行' . $i,
                'bank_store_name4' => '金沢文庫' . $i,
                'account4' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no4' => $i . '234567',
                'bank_name5' => '三井住友銀行' . $i,
                'bank_store_name5' => '金沢文庫' . $i,
                'account5' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no5' => $i . '234567',
                'bank_name6' => '三井住友銀行' . $i,
                'bank_store_name6' => '金沢文庫' . $i,
                'account6' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no6' => $i . '234567',
                'logo' => Str::random(100),
                'order' => $i,
            ]);
        }

        for($i = 1; $i <= count($names); $i++) {
            DB::table('m_stores')->insert([
                'company_id' => 2,
                'name' => $names[$i-1],
                'short_name' => $names[$i-1],
                'furigana' => 'テンポ' . ($i + 1),
                'tel_no' => '080-1111-222'.$i,
                'fax_no' => '080-1111-222'.($i+5),
                'post_no' => '1540005',
                'jisx0401_code' => rand(1,47),
                'jisx0402_code' => rand(10000,99999),
                'prefecture' => $i,
                'city' => '台東区'.$i,
                'address' => '浅草橋5-5-'.$i,
                'building_name' => 'キムラビル'.$i.'F',
                'bank_account' => null,
                'bank_account2' => null,
                'bank_account3' => null,
                'bank_account4' => null,
                'bank_account5' => null,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'free_dial' => '0123-000-00'.$i,
                'holder' => '山田太郎',
                'bank_name' => '三井住友銀行'.$i,
                'bank_store_name' => '金沢文庫'.$i,
                'account' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no' => $i.'234567'.$i,
                'bank_name2' => '三井住友銀行'.$i,
                'bank_store_name2' => '金沢文庫'.$i,
                'account2' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no2' => $i.'234567',
                'bank_name3' => '三井住友銀行'.$i,
                'bank_store_name3' => '金沢文庫'.$i,
                'account3' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no3' => $i.'234567',
                'bank_name4' => '三井住友銀行'.$i,
                'bank_store_name4' => '金沢文庫'.$i,
                'account4' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no4' => $i.'234567',
                'bank_name5' => '三井住友銀行'.$i,
                'bank_store_name5' => '金沢文庫'.$i,
                'account5' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no5' => $i.'234567',
                'bank_name6' => '三井住友銀行'.$i,
                'bank_store_name6' => '金沢文庫'.$i,
                'account6' => rand(1, 2),//1:普通口座 2:当座
                'bank_account_no6' => $i.'234567',
                'logo' => Str::random(100),
                'order' => $i,
            ]);
        }
    }
}
