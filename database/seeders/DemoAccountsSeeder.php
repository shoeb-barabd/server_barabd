<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bd = \App\Models\Country::where('iso2','BD')->first();
        $ae = \App\Models\Country::where('iso2','AE')->first();

        $acc1 = \App\Models\Account::create([
            'type'=>'business','name'=>'Baktier Ahmed Rony & Associates','display_name'=>'barabd',
            'country_id'=>$bd->id,'tax_id'=>'BN-12345','billing_address'=>[
                'line1'=>'49, Rasulpur, Jashore','city'=>'Jashore','state'=>'Khulna','postal_code'=>'7400'
            ],
            'status'=>'active'
        ]);

        $c1 = \App\Models\Contact::create([
            'account_id'=>$acc1->id,'first_name'=>'Sakib','last_name'=>'Barabd',
            'email'=>'sakib@example.com','phone'=>'+8801XXXXXXXX','designation'=>'IT Manager','is_primary'=>true
        ]);

        $acc2 = \App\Models\Account::create([
            'type'=>'business','name'=>'Dubai Branch Test','display_name'=>'barabd UAE',
            'country_id'=>$ae->id,'tax_id'=>null,'billing_address'=>null,'status'=>'prospect'
        ]);

        \App\Models\Contact::create([
            'account_id'=>$acc2->id,'first_name'=>'Rony','last_name'=>'Ahmed',
            'email'=>'rony@example.com','phone'=>'+9715XXXXXXXX','designation'=>'Sales','is_primary'=>true
        ]);
    }

}
