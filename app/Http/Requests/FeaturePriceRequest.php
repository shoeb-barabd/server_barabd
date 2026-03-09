<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeaturePriceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function rowId(): ?int
    {
        $p = $this->route('feature_price');
        return is_object($p) ? $p->getKey() : ($p ? (int)$p : null);
    }

    public function rules(): array
    {
        $id = $this->rowId();

        return [
            'customize_feature_id' => ['required','integer','exists:customize_features,id'],
            'location_id'        => ['required','integer','exists:locations,id'],
            'billing_cycle_id'   => ['required','integer','exists:billing_cycles,id'],
            'included_value'     => ['nullable','numeric','min:0'],
            'step'               => ['required','numeric','min:0.0001'],
            'price_per_step'     => ['required','numeric','min:0'],

            // unique triple
            Rule::unique('customize_feature_prices')
                ->ignore($id)
                ->where(fn($q)=> $q->where('customize_feature_id',$this->customize_feature_id)
                                   ->where('location_id',$this->location_id)
                                   ->where('billing_cycle_id',$this->billing_cycle_id))
        ];
    }
}
