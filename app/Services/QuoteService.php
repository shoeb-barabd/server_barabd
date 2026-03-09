<?php

namespace App\Services;

use App\Models\{
    Product, Location, BillingCycle, BasePrice, ProductFeature, FeaturePrice, AddOn, AddOnPrice
};
use Illuminate\Validation\ValidationException;

class QuoteService
{
    public function buildQuote(array $payload): array
    {
        $product  = Product::with('features')->findOrFail($payload['product_id']);
        $location = Location::findOrFail($payload['location_id']);
        $cycle    = BillingCycle::findOrFail($payload['billing_cycle_id']);

        $featuresInput = $payload['features'] ?? [];            // assoc: key => value
        $addOnsInput   = $payload['add_ons'] ?? [];              // [{key, qty}]

        // 1) Base price
        $base = BasePrice::where([
            'product_id' => $product->id,
            'location_id' => $location->id,
            'billing_cycle_id' => $cycle->id,
        ])->first();

        if (! $base) {
            throw ValidationException::withMessages([
                'base_price' => ['Base price not configured for product/location/cycle.']
            ]);
        }

        $lines = [];
        $subtotal = (float) $base->amount;
        $lines[] = [
            'type'   => 'base',
            'label'  => $product->name.' ('.$cycle->name.')',
            'amount' => round($base->amount, 2),
        ];

        // 2) Feature prices
        foreach ($product->features as $feature) {
            if (! array_key_exists($feature->key, $featuresInput)) {
                if ($feature->is_required) {
                    throw ValidationException::withMessages([
                        "features.{$feature->key}" => ["Required feature '{$feature->label}' missing."]
                    ]);
                }
                continue; // optional & not provided
            }

            $selected = $featuresInput[$feature->key];

            // 'select' options delta
            $optionDelta = 0.0;
            if ($feature->input_type === 'select') {
                $opt = collect($feature->options_json ?? [])->firstWhere('value', $selected);
                $optionDelta = (float) ($opt['delta'] ?? 0);
            }

            // Find pricing row (if applicable)
            $fp = FeaturePrice::where([
                'product_feature_id' => $feature->id,
                'location_id' => $location->id,
                'billing_cycle_id' => $cycle->id,
            ])->first();

            $amount = 0.0;
            if ($feature->input_type === 'number' || $feature->input_type === 'boolean') {
                if (! $fp) {
                    throw ValidationException::withMessages([
                        "features.{$feature->key}" => ["Price not configured for '{$feature->label}'."]
                    ]);
                }
                $value = $feature->input_type === 'boolean'
                    ? (int) (bool) $selected
                    : (float) $selected;

                $billable = max($value - (float) $fp->included_value, 0);
                $steps    = (int) ceil($billable / max((float)$fp->step, 1e-9));
                $amount   = $steps * (float) $fp->price_per_step;
            }

            $amount += $optionDelta;

            if ($amount > 0) {
                $lines[] = [
                    'type'   => 'feature',
                    'key'    => $feature->key,
                    'label'  => $feature->label,
                    'amount' => round($amount, 2),
                ];
                $subtotal += $amount;
            }
        }

        // 3) Add-ons
        foreach ($addOnsInput as $row) {
            $key = $row['key'] ?? null;
            if (! $key) continue;

            $addOn = AddOn::where('key', $key)->first();
            if (! $addOn) {
                throw ValidationException::withMessages(["add_ons" => ["Invalid add-on '{$key}'."]]);
            }
            $qty = $addOn->is_qty ? max((int)($row['qty'] ?? 0), 0) : (int) (bool) ($row['qty'] ?? 0);

            if ($qty <= 0) continue;

            $price = AddOnPrice::where([
                'add_on_id' => $addOn->id,
                'location_id' => $location->id,
                'billing_cycle_id' => $cycle->id,
            ])->first();

            if (! $price) {
                throw ValidationException::withMessages(["add_ons" => ["Price not configured for add-on '{$addOn->label}'."]]);
            }

            $amount = $qty * (float) $price->unit_price;

            $lines[] = [
                'type'   => 'add_on',
                'key'    => $addOn->key,
                'label'  => "{$addOn->label} × {$qty}",
                'amount' => round($amount, 2),
            ];
            $subtotal += $amount;
        }

        // 4) Tax
        $tax = round($subtotal * ((float) $location->tax_rate_percent) / 100, 2);
        $total = round($subtotal + $tax, 2);

        return [
            'currency'          => $location->currency_code,
            'location'          => $location->name,
            'billing_cycle'     => $cycle->code,
            'lines'             => $lines,
            'subtotal'          => round($subtotal, 2),
            'tax_rate_percent'  => (float) $location->tax_rate_percent,
            'tax'               => $tax,
            'total'             => $total,
        ];
    }
}
