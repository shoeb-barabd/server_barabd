<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = now()->toDateString();
        $map = [
            'BD' => ['VAT', 15.00, false],
            'AE' => ['VAT', 5.00,  false],
            'US' => ['SalesTax', 0.00, false], // placeholder
            'FR' => ['VAT', 20.00, false],
            'SL' => ['GST',  15.00, false],
        ];
        foreach ($map as $iso2 => [$name,$rate,$incl]) {
            $country = \App\Models\Country::where('iso2',$iso2)->first();
            if(!$country) continue;
            \App\Models\TaxRule::firstOrCreate(
                ['country_id'=>$country->id,'tax_name'=>$name,'effective_from'=>$today],
                ['rate_percent'=>$rate,'is_inclusive'=>$incl,'effective_to'=>null,'notes'=>null]
            );
        }
    }

}
