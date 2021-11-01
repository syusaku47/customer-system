<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MContractCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['ship','hagiwara','company1','company2'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_contract_companies')->insert([
//                'employee_id' => $i+1,
                'name' => $names[$i],
                'accunts_m' => $i,
                'prefix' => null,
                'accounts' => rand(0, 10),
                'operation_year' =>'200'.$i,
                'operation_month' =>$i,
                'datetime_ins' => date("Y-m-d H:i:s"),
                'capacity' => 2,
                'tel_no' => '0123-1234-000'.$i,
                'post_no' => '154000'.$i,
                'prefecture' => $i,
                'city' => '北斗市',
                'address' => '上町1-'.$i,
                'building_name' => 'ビル'.$i,
                'status' => 1, // 1:有償 2:無償
                'authority1' => 1, // 顧客一覧 ストリートビュー画像表示機能　0:なし 1:あり
                'authority2' => 1, // 案件一覧 ストリートビュー画像表示機能　0:なし 1:あり
                'authority3' => 1, // 顧客一覧 ルート検索機能　0:なし 1:あり
                'authority4' => 1, // 顧客ランク 顧客ランク自動更新機能　0:なし 1:あり
                'authority5' => 1, // 顧客ランク 顧客ランク自動更新ログ機能　0:なし 1:あり
                'is_valid' => rand(0,1), // 0:無効 1:有効
            ]);
        }
    }
}
