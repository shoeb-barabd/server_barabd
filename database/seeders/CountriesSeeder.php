<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['name'=>'Bangladesh','iso2'=>'BD','iso3'=>'BGD','phone_code'=>'880','is_active'=>1],
            ['name'=>'United Arab Emirates','iso2'=>'AE','iso3'=>'ARE','phone_code'=>'971','is_active'=>1],
            ['name'=>'United States','iso2'=>'US','iso3'=>'USA','phone_code'=>'1','is_active'=>1],
            ['name'=>'France','iso2'=>'FR','iso3'=>'FRA','phone_code'=>'33','is_active'=>1],
            ['name'=>'Sierra Leone','iso2'=>'SL','iso3'=>'SLE','phone_code'=>'232','is_active'=>1],
        ];
        foreach ($rows as $r) \App\Models\Country::updateOrCreate(['iso2'=>$r['iso2']], $r);
    }
}
