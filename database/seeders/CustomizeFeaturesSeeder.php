<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Location;
use App\Models\BillingCycle;
use App\Models\CustomizeFeature;
use App\Models\CustomizeFeaturePrice;

class CustomizeFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        $locations = Location::where('is_active', true)->get();
        $monthly   = BillingCycle::where('key', 'monthly')->first();
        $annual    = BillingCycle::where('key', 'annual')->first();

        if ($locations->isEmpty() || !$monthly || !$annual) {
            $this->command->warn('Skipping customize feature seed because locations or billing cycles are missing.');
            return;
        }

        $catalog = [
            'shared-hosting' => [
                ['key' => 'storage', 'label' => 'Storage', 'type' => 'number', 'unit' => 'GB', 'min' => 5, 'max' => 200, 'step' => 5, 'included' => 10, 'price' => 1.50],
                ['key' => 'bandwidth', 'label' => 'Bandwidth', 'type' => 'number', 'unit' => 'GB', 'min' => 50, 'max' => 2000, 'step' => 50, 'included' => 100, 'price' => 1.00],
                ['key' => 'websites', 'label' => 'Websites', 'type' => 'number', 'unit' => 'site', 'min' => 1, 'max' => 20, 'step' => 1, 'included' => 1, 'price' => 25.00],
            ],
            'vps-hosting' => [
                ['key' => 'cpu', 'label' => 'CPU Cores', 'type' => 'number', 'unit' => 'Core', 'min' => 1, 'max' => 32, 'step' => 1, 'included' => 1, 'price' => 6.00],
                ['key' => 'ram', 'label' => 'RAM', 'type' => 'number', 'unit' => 'GB', 'min' => 2, 'max' => 128, 'step' => 2, 'included' => 2, 'price' => 3.50],
                ['key' => 'ssd', 'label' => 'SSD Storage', 'type' => 'number', 'unit' => 'GB', 'min' => 40, 'max' => 2000, 'step' => 20, 'included' => 60, 'price' => 1.20],
            ],
            'cloud-storage-service' => [
                ['key' => 'storage', 'label' => 'Cloud Storage', 'type' => 'number', 'unit' => 'GB', 'min' => 100, 'max' => 10000, 'step' => 100, 'included' => 200, 'price' => 0.90],
                ['key' => 'backup', 'label' => 'Auto Backup', 'type' => 'boolean', 'included' => 0, 'price' => 3.00],
                ['key' => 'api', 'label' => 'API Access', 'type' => 'boolean', 'included' => 0, 'price' => 2.00],
            ],
        ];

        foreach ($catalog as $categorySlug => $features) {
            $category = Category::where('slug', $categorySlug)->first();
            if (!$category) {
                $this->command->warn("Category '{$categorySlug}' not found; skipping customize features.");
                continue;
            }

            foreach ($features as $f) {
                $feature = CustomizeFeature::updateOrCreate(
                    ['category_id' => $category->id, 'key' => $f['key']],
                    [
                        'label'      => $f['label'],
                        'input_type' => $f['type'],
                        'unit'       => $f['unit'] ?? null,
                        'min'        => $f['min'] ?? null,
                        'max'        => $f['max'] ?? null,
                        'step'       => $f['step'] ?? 1,
                        'options_json' => $f['options'] ?? null,
                        'is_required'  => false,
                    ]
                );

                foreach ($locations as $loc) {
                    $this->priceRow($feature, $loc->id, $monthly->id, $f);
                    $this->priceRow($feature, $loc->id, $annual->id, $f, true);
                }
            }
        }
    }

    protected function priceRow(CustomizeFeature $feature, int $locationId, int $cycleId, array $def, bool $annual = false): void
    {
        $price = $annual ? ($def['price'] ?? 0) * 12 : ($def['price'] ?? 0);

        CustomizeFeaturePrice::updateOrCreate(
            [
                'customize_feature_id' => $feature->id,
                'location_id' => $locationId,
                'billing_cycle_id' => $cycleId,
            ],
            [
                'included_value' => $def['included'] ?? 0,
                'step' => $def['step'] ?? 1,
                'price_per_step' => $price,
            ]
        );
    }
}
