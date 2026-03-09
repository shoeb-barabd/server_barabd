<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{
    Category,
    Product,
    BasePrice,
    AddOn,
    AddOnPrice,
    Location,
    BillingCycle,
};

class CompletePricingCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $locations = $this->seedLocations();
        [$monthly, $annual] = $this->seedBillingCycles();

        $catalog = $this->catalog();

        foreach ($catalog['categories'] as $catKey => $catData) {
            $category = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                ['name' => $catData['name'], 'is_active' => 1]
            );

            // Products + base prices per location
            foreach ($catData['products'] as $productLabel => $prices) {
                $product = Product::updateOrCreate(
                    ['slug' => Str::slug($catData['slug'] . '-' . $productLabel)],
                    ['category_id' => $category->id, 'name' => $productLabel, 'is_active' => 1]
                );

                foreach ($prices as $locKey => $amountMonthly) {
                    $loc = $locations[$locKey] ?? null;
                    if (!$loc) {
                        continue;
                    }
                    BasePrice::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'location_id' => $loc->id,
                            'billing_cycle_id' => $monthly->id,
                        ],
                        ['amount' => $amountMonthly]
                    );
                    BasePrice::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'location_id' => $loc->id,
                            'billing_cycle_id' => $annual->id,
                        ],
                        ['amount' => round($amountMonthly * 12, 2)]
                    );
                }
            }

            // Add-ons per category (if any)
            foreach ($catData['addons'] as $addonKey => $addonInfo) {
                $addon = AddOn::updateOrCreate(
                    ['key' => $addonKey],
                    [
                        'label' => $addonInfo['label'],
                        'category_id' => $category->id,
                        'unit_type' => 'recurring',
                        'is_qty_based' => false,
                        'max_qty' => 1,
                        'is_active' => 1,
                    ]
                );

                foreach ($addonInfo['prices'] as $locKey => $unitPrice) {
                    $loc = $locations[$locKey] ?? null;
                    if (!$loc) {
                        continue;
                    }
                    AddOnPrice::updateOrCreate(
                        [
                            'add_on_id' => $addon->id,
                            'location_id' => $loc->id,
                            'billing_cycle_id' => $monthly->id,
                        ],
                        ['unit_price' => $unitPrice]
                    );
                    AddOnPrice::updateOrCreate(
                        [
                            'add_on_id' => $addon->id,
                            'location_id' => $loc->id,
                            'billing_cycle_id' => $annual->id,
                        ],
                        ['unit_price' => round($unitPrice * 12, 2)]
                    );
                }
            }
        }
    }

    protected function seedLocations(): array
    {
        $map = [
            'bd'    => ['Bangladesh (BDIX)', 'BDT', 15],
            'uae'   => ['United Arab Emirates', 'AED', 5],
            'eu'    => ['Europe', 'EUR', 20],
            'us'    => ['United States', 'USD', 0],
            'asia'  => ['Asia', 'USD', 0],
            'africa'=> ['Africa', 'USD', 0],
        ];

        $locations = [];
        foreach ($map as $key => [$name, $currency, $tax]) {
            $locations[$key] = Location::updateOrCreate(
                ['name' => $name],
                [
                    'currency_code' => $currency,
                    'tax_rate_percent' => $tax,
                    'is_active' => 1,
                ]
            );
        }

        return $locations;
    }

    protected function seedBillingCycles(): array
    {
        $monthly = BillingCycle::firstOrCreate(['key' => 'monthly'], ['name' => 'Monthly', 'months' => 1, 'is_active' => 1]);
        $annual  = BillingCycle::firstOrCreate(['key' => 'annual'],  ['name' => 'Annual',  'months' => 12, 'is_active' => 1]);
        return [$monthly, $annual];
    }

    /**
     * Structured catalog derived from the provided pricing document.
     */
    protected function catalog(): array
    {
        return [
            'categories' => [
                'shared-hosting' => [
                    'slug' => 'shared-hosting',
                    'name' => 'Shared Hosting',
                    'products' => [
                        'Solo'     => ['bd' => 99,  'uae' => 4,   'eu' => 1.49, 'us' => 0.99, 'asia' => 0.89, 'africa' => 1.49],
                        'Infinity' => ['bd' => 199, 'uae' => 10,  'eu' => 2.99, 'us' => 2.99, 'asia' => 2.49, 'africa' => 3.49],
                        'Pro'      => ['bd' => 349, 'uae' => 18,  'eu' => 5.99, 'us' => 5.79, 'asia' => 4.99, 'africa' => 6.29],
                        'Business' => ['bd' => 699, 'uae' => 35,  'eu' => 9.99, 'us' => 10.59,'asia' => 8.99, 'africa' => 11.99],
                    ],
                    'addons' => [
                        'ssl_free'       => ['label' => 'Free SSL Certificate',      'prices' => ['bd' => 0]],
                        'mysql_db'       => ['label' => 'MySQL Database',            'prices' => ['bd' => 20]],
                        'speed_boost'    => ['label' => 'Speed Boost',               'prices' => ['bd' => 30]],
                        'cpanel_access'  => ['label' => 'cPanel Access',             'prices' => ['bd' => 40]],
                        'daily_backup'   => ['label' => 'Daily Backup',              'prices' => ['bd' => 50]],
                        'malware_scan'   => ['label' => 'Malware Scanning',          'prices' => ['bd' => 60]],
                        'cdn_service'    => ['label' => 'CDN Service',               'prices' => ['bd' => 80]],
                        'priority_support'=> ['label' => '24/7 Priority Support',    'prices' => ['bd' => 100]],
                    ],
                ],

                'vps-hosting' => [
                    'slug' => 'vps-hosting',
                    'name' => 'VPS Hosting',
                    'products' => [
                        'VPS-2G'  => ['bd' => 1250, 'uae' => 29,  'eu' => 9.99, 'us' => 8.99, 'asia' => 7.99, 'africa' => 9.50],
                        'VPS-4G'  => ['bd' => 2500, 'uae' => 59,  'eu' => 14.99,'us' => 14.99,'asia' => 12.99,'africa' => 16.50],
                        'VPS-6G'  => ['bd' => 3750, 'uae' => 89,  'eu' => 19.99,'us' => 19.99,'asia' => 17.99,'africa' => 22.90],
                        'VPS-8G'  => ['bd' => 5000, 'uae' => 119, 'eu' => 29.99,'us' => 29.99,'asia' => 24.99,'africa' => 32.90],
                        'VPS-16G' => ['bd' => 7500, 'uae' => 179, 'eu' => 49.99,'us' => 49.99,'asia' => 39.99,'africa' => 54.90],
                        'VPS-32G' => ['bd' => 11250,'uae' => 349, 'eu' => 79.99,'us' => 79.99,'asia' => 69.99,'africa' => 89.90],
                    ],
                    'addons' => [
                        'adv_firewall'   => ['label' => 'Advanced Firewall',         'prices' => ['bd' => 100]],
                        'vpn'            => ['label' => 'End-to-End Encrypted VPN',  'prices' => ['bd' => 125]],
                        'monitoring'     => ['label' => 'Server Monitoring',         'prices' => ['bd' => 150]],
                        'daily_backup'   => ['label' => 'Daily Backup Service',      'prices' => ['bd' => 200]],
                        'ddos_protect'   => ['label' => 'Advanced DDoS Protection',  'prices' => ['bd' => 200]],
                        'auto_backup'    => ['label' => 'Automated Daily Backup',    'prices' => ['bd' => 250]],
                        'dedicated_ip'   => ['label' => 'Dedicated Public IP',       'prices' => ['bd' => 300]],
                        'priority_support'=> ['label' => '24/7 Priority Support',    'prices' => ['bd' => 300]],
                        'managed_vps'    => ['label' => 'Managed VPS Service',       'prices' => ['bd' => 500]],
                    ],
                ],

                'cloud-storage' => [
                    'slug' => 'cloud-storage',
                    'name' => 'Cloud Storage / Drive',
                    'products' => [
                        'Personal (100GB)'  => ['bd' => 200,  'uae' => 5,   'eu' => 1.99, 'us' => 1.99, 'asia' => 1.79, 'africa' => 2.50],
                        'Business (500GB)'  => ['bd' => 800,  'uae' => 20,  'eu' => 7.99, 'us' => 7.99, 'asia' => 6.99, 'africa' => 8.50],
                        'Enterprise (2TB)'  => ['bd' => 2000, 'uae' => 50,  'eu' => 19.99,'us' => 19.99,'asia' => 17.99,'africa' => 21.50],
                        'Unlimited'         => ['bd' => 5000, 'uae' => 125, 'eu' => 49.99,'us' => 49.99,'asia' => 44.99,'africa' => 52.50],
                    ],
                    'addons' => [
                        'api_access'     => ['label' => 'API Access',                 'prices' => ['bd' => 120]],
                        'version_history'=> ['label' => 'Extended Version History',    'prices' => ['bd' => 100]],
                        'sharing_controls'=>['label' => 'Advanced Sharing Controls',   'prices' => ['bd' => 150]],
                        'backup_recovery'=> ['label' => 'Advanced Backup & Recovery',  'prices' => ['bd' => 180]],
                        'priority_support'=>['label' => 'Priority Support',            'prices' => ['bd' => 200]],
                        'office_suite'   => ['label' => 'Office Suite Integration',   'prices' => ['bd' => 250]],
                        'sso'            => ['label' => 'SSO Integration',            'prices' => ['bd' => 300]],
                    ],
                ],

                'corporate-mail' => [
                    'slug' => 'corporate-mail',
                    'name' => 'Corporate Mail Hosting',
                    'products' => [
                        'Starter Mail'    => ['bd' => 300,  'uae' => 7.5,  'eu' => 2.99, 'us' => 2.99, 'asia' => 2.49, 'africa' => 3.50],
                        'Business Mail'   => ['bd' => 600,  'uae' => 15,   'eu' => 5.99, 'us' => 5.99, 'asia' => 4.99, 'africa' => 6.50],
                        'Enterprise Mail' => ['bd' => 1200, 'uae' => 30,   'eu' => 11.99,'us' => 11.99,'asia' => 9.99, 'africa' => 12.50],
                    ],
                    'addons' => [
                        'mdm'             => ['label' => 'Mobile Device Management',   'prices' => ['bd' => 100]],
                        'api_access'      => ['label' => 'API Access & Integration',   'prices' => ['bd' => 120]],
                        'advanced_sec'    => ['label' => 'Advanced Security (ATP)',    'prices' => ['bd' => 150]],
                        'email_encrypt'   => ['label' => 'Email Encryption (S/MIME)',  'prices' => ['bd' => 200]],
                        'priority_support'=> ['label' => 'Priority Support',            'prices' => ['bd' => 250]],
                        'compliance'      => ['label' => 'Compliance & Legal Hold',    'prices' => ['bd' => 300]],
                    ],
                ],

                'datacenter' => [
                    'slug' => 'datacenter',
                    'name' => 'Datacenter Facilities',
                    'products' => [
                        '1U Server Space' => ['bd' => 2500,  'uae' => 59,  'eu' => 19.99, 'us' => 17.99, 'asia' => 14.99, 'africa' => 19.99],
                        '2U Server Space' => ['bd' => 4500,  'uae' => 109, 'eu' => 34.99, 'us' => 32.99, 'asia' => 27.99, 'africa' => 36.99],
                        '8U Server Space' => ['bd' => 16000, 'uae' => 399, 'eu' => 129.99,'us' => 119.99,'asia' => 99.99, 'africa' => 139.99],
                        'Square Feet Space' => ['bd' => 1200, 'uae' => 29,  'eu' => 9.99,  'us' => 8.99,  'asia' => 7.99,  'africa' => 10.99],
                        'Network Facility'  => ['bd' => 3000, 'uae' => 79,  'eu' => 24.99, 'us' => 22.99, 'asia' => 19.99, 'africa' => 26.99],
                        'Public IP'         => ['bd' => 500,  'uae' => 15,  'eu' => 4.99,  'us' => 3.99,  'asia' => 2.99,  'africa' => 4.99],
                        'Dedicated Cooling' => ['bd' => 1800, 'uae' => 45,  'eu' => 14.99, 'us' => 12.99, 'asia' => 11.99, 'africa' => 15.99],
                    ],
                    'addons' => [
                        'cross_connect'   => ['label' => 'Cross Connect Service',      'prices' => ['bd' => 500]],
                        'cctv'            => ['label' => '24/7 CCTV Monitoring',       'prices' => ['bd' => 600]],
                        'fire_suppression'=> ['label' => 'Fire Suppression System',     'prices' => ['bd' => 800]],
                        'remote_hands'    => ['label' => 'Remote Hands Service',        'prices' => ['bd' => 1000]],
                        'biometric'       => ['label' => 'Biometric Access Control',    'prices' => ['bd' => 1200]],
                        'ups_redundant'   => ['label' => 'Redundant UPS System',        'prices' => ['bd' => 1500]],
                        'managed_switch'  => ['label' => 'Managed Network Switch',      'prices' => ['bd' => 1500]],
                        'generator_backup'=> ['label' => 'Generator Backup',            'prices' => ['bd' => 2000]],
                    ],
                ],
            ],
        ];
    }
}
