<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MSignaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ( range(1,5) as $i){
            DB::table('m_signatures')->insert([
                'item' => "項目".$i,
                'name' => "名称".$i,
            ]);
        }
    }
}
