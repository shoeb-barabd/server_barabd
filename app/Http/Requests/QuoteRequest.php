<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_id'       => ['required','integer','exists:products,id'],
            'location_id'      => ['required','integer','exists:locations,id'],
            'billing_cycle_id' => ['required','integer','exists:billing_cycles,id'],

            // assoc array: key => value
            'features'         => ['nullable','array'],

            // array of objects: [{key, qty}]
            'add_ons'          => ['nullable','array'],
            'add_ons.*.key'    => ['required_with:add_ons','string'],
            'add_ons.*.qty'    => ['nullable','numeric'],
        ];
    }
}
