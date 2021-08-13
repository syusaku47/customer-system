<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.<br>
     * ファイルデータ
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 3) as $val) {
            DB::table('t_files')->insert([
                'customer_id' => 1,
                'project_id' => $val,
//                'company_id' => $val, // 後で追加
                'file_name' => 'ファイル' . $val,
                'format' => '.jpg',
                'size' => 10 . $val,
                'comment' => 'コメント' . $val,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'last_updated_by' => '山田太郎' . $val,
            ]);
        }
    }
}
