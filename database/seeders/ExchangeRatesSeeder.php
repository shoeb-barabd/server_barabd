<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExchangeRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = now()->toDateString();
        $pairs = [
            ['base'=>'USD','quote'=>'BDT','rate'=>120.00],
            ['base'=>'USD','quote'=>'AED','rate'=>3.6725],
            ['base'=>'USD','quote'=>'EUR','rate'=>0.92],
            ['base'=>'USD','quote'=>'SLE','rate'=>22.00],
        ];
        foreach ($pairs as $p) {
            \App\Models\ExchangeRate::firstOrCreate(
                ['base_currency_code'=>$p['base'],'quote_currency_code'=>$p['quote'],'valid_from'=>$today],
                ['rate'=>$p['rate'],'valid_to'=>null]
            );
        }
    }
}
