<?php

namespace Database\Seeders;

use App\Models\DomainTld;
use Illuminate\Database\Seeder;

class DomainTldSeeder extends Seeder
{
    public function run(): void
    {
        $tlds = [
            ['tld' => '.com',     'register_price' => 1150, 'renew_price' => 1350, 'transfer_price' => 1150, 'sort_order' => 1],
            ['tld' => '.net',     'register_price' => 1350, 'renew_price' => 1500, 'transfer_price' => 1350, 'sort_order' => 2],
            ['tld' => '.org',     'register_price' => 1250, 'renew_price' => 1450, 'transfer_price' => 1250, 'sort_order' => 3],
            ['tld' => '.info',    'register_price' =>  950, 'renew_price' => 1800, 'transfer_price' =>  950, 'sort_order' => 4],
            ['tld' => '.xyz',     'register_price' =>  250, 'renew_price' => 1350, 'transfer_price' => 1200, 'sort_order' => 5],
            ['tld' => '.online',  'register_price' =>  350, 'renew_price' => 3500, 'transfer_price' => 3000, 'sort_order' => 6],
            ['tld' => '.shop',    'register_price' =>  350, 'renew_price' => 3200, 'transfer_price' => 2800, 'sort_order' => 7],
            ['tld' => '.co',      'register_price' => 2800, 'renew_price' => 3000, 'transfer_price' => 2800, 'sort_order' => 8],
            ['tld' => '.io',      'register_price' => 4500, 'renew_price' => 5000, 'transfer_price' => 4500, 'sort_order' => 9],
            ['tld' => '.dev',     'register_price' => 1500, 'renew_price' => 1600, 'transfer_price' => 1500, 'sort_order' => 10],
            ['tld' => '.me',      'register_price' => 1800, 'renew_price' => 2200, 'transfer_price' => 1800, 'sort_order' => 11],
            ['tld' => '.site',    'register_price' =>  300, 'renew_price' => 3000, 'transfer_price' => 2500, 'sort_order' => 12],
            ['tld' => '.store',   'register_price' =>  350, 'renew_price' => 5000, 'transfer_price' => 4500, 'sort_order' => 13],
            ['tld' => '.tech',    'register_price' =>  400, 'renew_price' => 4500, 'transfer_price' => 4000, 'sort_order' => 14],
            ['tld' => '.bd',      'register_price' => 2500, 'renew_price' => 2500, 'transfer_price' =>    0, 'sort_order' => 15],
        ];

        foreach ($tlds as $tld) {
            DomainTld::updateOrCreate(
                ['tld' => $tld['tld']],
                array_merge($tld, [
                    'currency'  => 'BDT',
                    'is_active' => true,
                ])
            );
        }
    }
}
