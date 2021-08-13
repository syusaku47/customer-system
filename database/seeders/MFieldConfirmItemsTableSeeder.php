<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MFieldConfirmItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ( range(1,5) as $i){
            DB::table('m_field_confirm_items')->insert([
                'item' => "現場準備確認項目".$i,
            ]);
        }
    }
}
