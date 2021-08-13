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
        foreach (range(1, 3) as $val) {
            DB::table('t_customers')->insert([
                'sales_shop' => $val, // 店舗マスタのID
                'sales_contact' => $val, // 社員マスタのID
                'name' => '鈴木一郎',
                'keisho' => '様',
                'furigana' => 'スズキイチロウ',
                'tel_no' => '080-1111-2221',
                'tel_no2' => '080-1111-2222',
                'tel_no3' => '080-1111-2223',
                'is_deficiency' => 0, // 0:false 1:true
                'fax_no' => '080-1111-2224',
                'mail_address' => 'test@gmail.com',
                'mail_address2' => 'test2@gmail.com',
                'mail_address3' => 'test@3gmail.com',
                'post_no' => '1540005',
                'prefecture' => ModelBase::PREFECTURE[$val],
                'city' => '台東区',
                'address' => '浅草橋5-5-5',
                'building_name' => 'キムラビル4F',
                'line_id' => '1',
                'facebook_id' => '1',
                'twitter_id' => '1',
                'instagram_id' => '1',
                'rank' => $val, // 顧客ランクマスタのID
                'rank_filter' => 1, // 1:のみ 2:以上 3:以下
                'estimated_rank' => $val, // 顧客見込みランクマスタのID
                'estimated_rank_filter' => 1, // 1:OB 2:見込み
                'source_id' => 1,  // 発生源マスタのID
                'area_id' => 1, // エリアマスタのID
                'building_category_id' => 1, // 建物分類マスタのID
                'madori_id' => 1, // 間取りマスタのID
                'building_age' => 1,
                'completion_start_year'=> '2021',
                'completion_start_month'=> '01',
                'completion_end_year'=> '2021',
                'completion_end_month'=> '06',
                'last_completion_start_year'=> '2020',
                'last_completion_start_month'=> '06',
                'last_completion_end_year'=> '2020',
                'last_completion_end_month'=> '12',
                'total_work_price_min'=> 100000,
                'total_work_price_max'=> 200000,
                'work_times_min'=> 5,
                'work_times_max'=> 10,
                'tag_list' => '1 2 3', // 関連タグマスタのID を半角スペース区切りで結合し格納
                'part_list' => '3 4', // 部位マスタのID を半角スペース区切りで結合し格納
                'expected_part_list' => '1 2 3', // 部位マスタのID を半角スペース区切りで結合し格納
                'remarks' => '特になし',
                'memo1' => '社内メモ1',
                'memo2' => '社内メモ2',
                'my_car_type' => '1 2 3', // マイカー種別マスタのID を半角スペース区切りで結合し格納
                'my_car_type_other' => 'その他',
                'introducer' => '鈴木一郎',
                'wedding_anniversary' => '2021-05-20',
                'friend_meeting' => '入会',
                'reform_album' => '有り',
                'case_permit' => '許可',
                'field_tour_party' => '不許可',
                'lat' => '35.6999478238869',
                'lng' => '139.780773498344',
                'ob_flag' => 2,
                'is_editing' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'last_updated_by' => '山田太郎',
            ]);
        }
    }
}
