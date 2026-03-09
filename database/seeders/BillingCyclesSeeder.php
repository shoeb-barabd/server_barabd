<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillingCyclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['key'=>'monthly',     'name'=>'Monthly',     'months'=>1],
            ['key'=>'quarterly',   'name'=>'Quarterly',   'months'=>3],
            ['key'=>'semi_annual', 'name'=>'Semi-Annual', 'months'=>6],
            ['key'=>'annual',      'name'=>'Annual',      'months'=>12],
        ];
        foreach ($rows as $r) {
            \App\Models\BillingCycle::updateOrCreate(['key'=>$r['key']], $r + ['is_active'=>true]);
        }
    }
}
