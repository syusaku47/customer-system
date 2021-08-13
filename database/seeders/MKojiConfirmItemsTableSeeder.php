<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MKojiConfirmItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ( range(1,5) as $i){
            DB::table('m_koji_confirm_items')->insert([
                'item' => "項目".$i,
                'caution' => "注意書き".$i,
            ]);
        }
    }
}
