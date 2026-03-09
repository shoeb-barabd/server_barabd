<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['code'=>'BDT','name'=>'Bangladeshi Taka','symbol'=>'৳','decimal_places'=>2,'is_active'=>1],
            ['code'=>'USD','name'=>'US Dollar','symbol'=>'$','decimal_places'=>2,'is_active'=>1],
            ['code'=>'AED','name'=>'UAE Dirham','symbol'=>'د.إ','decimal_places'=>2,'is_active'=>1],
            ['code'=>'EUR','name'=>'Euro','symbol'=>'€','decimal_places'=>2,'is_active'=>1],
            ['code'=>'SLE','name'=>'Sierra Leonean Leone','symbol'=>'Le','decimal_places'=>2,'is_active'=>1],
        ];
        foreach ($rows as $r) \App\Models\Currency::updateOrCreate(['code'=>$r['code']], $r);
    }
}
