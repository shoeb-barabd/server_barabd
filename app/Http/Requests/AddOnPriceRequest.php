<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddOnPriceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function rowId(): ?int
    {
        $p = $this->route('add_on_price');
        return is_object($p) ? $p->getKey() : ($p ? (int)$p : null);
    }

    public function rules(): array
    {
        $id = $this->rowId();

        return [
            'add_on_id'        => ['required','integer','exists:add_ons,id'],
            'location_id'      => ['required','integer','exists:locations,id'],
            'billing_cycle_id' => ['required','integer','exists:billing_cycles,id'],
            'unit_price'       => ['required','numeric','min:0'],

            Rule::unique('add_on_prices')
                ->ignore($id)
                ->where(fn($q)=> $q->where('add_on_id',$this->add_on_id)
                                   ->where('location_id',$this->location_id)
                                   ->where('billing_cycle_id',$this->billing_cycle_id))
        ];
    }
}
