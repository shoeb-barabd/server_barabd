<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Location;

class LocationsSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            ['name'=>'Bangladesh',   'iso2'=>'BD'],
            ['name'=>'Dubai',        'iso2'=>'AE'], // Dubai maps to UAE
            ['name'=>'USA',          'iso2'=>'US'],
            ['name'=>'France',       'iso2'=>'FR'],
            ['name'=>'Sierra Leone', 'iso2'=>'SL'],
        ];

        foreach ($map as $row) {
            $country = Country::where('iso2', $row['iso2'])->first();
            Location::updateOrCreate(
                ['name' => $row['name']],
                ['country_id' => $country?->id, 'is_active' => true]
            );
        }
    }
}
