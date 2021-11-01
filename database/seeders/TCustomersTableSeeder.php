<?php

namespace Database\Seeders;

use App\Models\ModelBase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TCustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 顧客データ
     *
     * @return void
     */
    public function run()
    {
        $names = ['田中一郎', '鈴木一郎', '古川理絵'];
//        company_id =1のseeder
        foreach ($names as $key => $name) {
            DB::table('t_customers')->insert([
                'internal_id' => $key + 1,
                'company_id' => 1,
//                'sales_shop' => $val, // 店舗マスタのID
                'name' => $name,
                'keisho' => '様',
                'name_short' => '様',
                'furigana' => 'スズキイチロウ',
                'tel_no' => '080-1111-2221',
                'tel_no2' => '080-1111-2222',
                'tel_no3' => '080-1111-2223',
                'fax_no' => '080-1111-2224',
//                'is_deficiency' => 0, // 0:false 1:true
                'post_no' => '154000'.$key,
                'jisx0401_code' => '1'.$key,
                'jisx0402_code' => '4000'.$key,
                'prefecture' => $key + 1,
                'city' => '台東区',
                'address' => '浅草橋5-5-5',
                'building_name' => 'キムラビル4F',
                'mail_address' => 'test@gmail.com',
                'mail_address2' => 'test2@gmail.com',
                'mail_address3' => 'test@3gmail.com',
                'area_id' => $key + 1, // エリアマスタのID
                'bank_account' => null,
                'sales_contact' => 'test', // 社員マスタのCD
                'building_category_id' => $key + 1, // 建物分類マスタのID
                'madori_id' => $key + 1, // 間取りマスタのID
                'wedding_anniversary' => date('Y-m-d'),
                'building_age' => $key + 1,
                'rank_koji' => $key + 1, // 顧客ランクマスタのID
                'remarks' => '特になし',
                'datetime_upd' => date('Y-m-d'),
                'last_updated_by' => $name,
                'valid_flag' => rand(0,1), // 0:無効 1:有効
                'history' => '家歴',
                'estimated_rank' => $key + 1, // 顧客見込みランクマスタのID
                'ob_flag' => rand(1,2), // 1:OB 2:見込み

                'my_car_type_other' => 'その他',

                'source_id' => $key + 1,  // 発生源マスタのID
                'employee_id' => $key + 1,
                'store_id' => $key + 1,  // 店舗マスタのID
                'line_id' => '1',
                'facebook_id' => '1',
                'twitter_id' => '1',
                'instagram_id' => '1',

//                'rank_filter' => 1, // 1:のみ 2:以上 3:以下
//                'estimated_rank_filter' => 1, // 1:OB 2:見込み
//                'completion_start_year'=> '2021',
//                'completion_start_month'=> '01',
//                'completion_end_year'=> '2021',
//                'completion_end_month'=> '06',
//                'last_completion_start_year'=> '2020',
//                'last_completion_start_month'=> '06',
//                'last_completion_end_year'=> '2020',
//                'last_completion_end_month'=> '12',
//                'total_work_price_min'=> 100000,
//                'total_work_price_max'=> 200000,
//                'work_times_min'=> 5,
//                'work_times_max'=> 10,
                'tag_list' => '1 2 3', // 関連タグマスタのID を半角スペース区切りで結合し格納
                'tag_list_other' => 'その他、自由項目', // 関連タグマスタのID を半角スペース区切りで結合し格納
                'part_list' => '3 4', // 部位マスタのID を半角スペース区切りで結合し格納
                'expected_part_list' => '1 2 3', // 部位マスタのID を半角スペース区切りで結合し格納
                'memo1' => '社内メモ1',
                'memo2' => '社内メモ2',
                'my_car_type' => '1 2 3', // マイカー種別マスタのID を半角スペース区切りで結合し格納
                'introducer' => '鈴木一郎',
//                'friend_meeting' => '入会',
//                'reform_album' => '有り',
//                'case_permit' => '許可',
//                'field_tour_party' => '不許可',
                'lat' => '35.6999478238869',
                'lng' => '139.780773498344',
                'rank_last' =>$key + 1,
//                'is_editing' => 0,
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
