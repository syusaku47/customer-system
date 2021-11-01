<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MInquiriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 問い合わせマスタ
     * @return void
     */
    public function run()
    {
        $names =
            [
                '案件について',
                '緊急！！',
                '物販',
                '資料請求・問合せ',
                'ご来店',
                'クレーム',
                'リフォーム相談',
                '修理依頼'
            ];

        foreach ($names as $i => $name) {
//            company_id = 1 バージョン
            DB::table('m_inquiries')->insert([
                'company_id' => 1,
                'internal_id' => $i + 1,
                'name' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }

        foreach ($names as $i => $name) {
//            company_id = 2 バージョン
            DB::table('m_inquiries')->insert([
                'company_id' => 2,
                'internal_id' => $i + 1,
                'name' => $name,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
                'order' => rand(1, 100),
            ]);
        }
    }
}

