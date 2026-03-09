<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BasePriceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function rowId(): ?int
    {
        $p = $this->route('base_price');
        return is_object($p) ? $p->getKey() : ($p ? (int)$p : null);
    }

    public function rules(): array
    {
        $id = $this->rowId();
        return [
            'product_id'       => ['required','integer','exists:products,id'],
            'location_id'      => ['required','integer','exists:locations,id'],
            'billing_cycle_id' => ['required','integer','exists:billing_cycles,id'],
            'amount'           => ['required','numeric','min:0'],

            // unique per product+location+cycle
            Rule::unique('base_prices')
                ->ignore($id)
                ->where(fn($q)=> $q->where('product_id',$this->product_id)
                                   ->where('location_id',$this->location_id)
                                   ->where('billing_cycle_id',$this->billing_cycle_id))
        ];
    }

    public function messages(): array
    {
        return ['base_prices_unique' => 'This product/location/cycle already has a base price.'];
    }
}
