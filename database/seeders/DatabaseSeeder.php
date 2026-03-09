<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            CountriesSeeder::class,
            CurrenciesSeeder::class,
            ExchangeRatesSeeder::class,
            TaxRulesSeeder::class,
            DemoAccountsSeeder::class,
            LocationsSeeder::class,
            BillingCyclesSeeder::class,
            CustomizeFeaturesSeeder::class,
            CompletePricingCatalogSeeder::class,
            UsersSeeder::class,
           

        ]);
    }
}
