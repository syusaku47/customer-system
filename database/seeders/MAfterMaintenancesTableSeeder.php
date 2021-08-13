<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MAfterMaintenancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 5) as $i) {
            DB::table('m_after_maintenances')->insert([
                'ins_expected_date' => $i,
                'is_valid' => rand(0, 1), // 0:無効 1:有効
            ]);
        }
    }
}
