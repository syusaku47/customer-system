<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MEmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * 社員マスタ
     *
     * @return void
     */
    public function run()
    {
        // テスト環境用マスタデータ（現行システムから抽出）
        $names = ['村岡　英太郎', '小里　和仁', '岡本　敦勇', '山田　太郎', '山田次郎', '菅　直人', '神谷　信吾', '宮城　雅江', '杉浦 史雄', '松岡　英二郎', '黒井　辰', '岩田　大介', '神谷信吾', '高野　健人', 'takano2', 'takano3', 'takano5', 'テスト太郎'];
        for($i = 0; $i <= count($names) - 1; $i++) {
            DB::table('m_employees')->insert([
                'employee_cd' => '0002',
                'password' => 'ma123456'.$i,
                'store_id' => rand(1, 3),
                'name' => $names[$i],
                'short_name' => $names[$i],
                'furigana' => 'タマダタロウ',
                'job_title' => '正社員',
                'mail_address' => 'test'.$i.'@gmail.com',
                'sales_target' => '1000000',
                'is_valid' => 1, // 0:無効 1:有効
                'authority1' => rand(0, 1), // 1:権限有 0:権限無（自分の担当以外のデータ登録、修正が可能な権限）
                'authority2' => rand(0, 1), // 1:権限有 0:権限無（入金処理、原価処理の操作が可能な権限）
                'authority3' => rand(0, 1), // 1:権限有 0:権限無（経理担当用（ログイン時に最初に請求入金画面表示できる権限））
                'authority4' => rand(0, 1), // 1:権限有 0:権限無（マスタ管理の操作が可能な権限）
            ]);
        }
    }
}
