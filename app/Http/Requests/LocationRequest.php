<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gate/role is handled by middleware on the route group
    }

    public function rules(): array
    {
        $id = $this->route('location')?->id;

        return [
            'name'             => ['required','string','max:100', Rule::unique('locations','name')->ignore($id)],
            'country_id'       => ['nullable','integer','exists:countries,id'],
            'currency_code'    => ['required','string','size:3'],
            'tax_rate_percent' => ['required','numeric','min:0','max:100'],
            'is_active'        => ['required','boolean'],
        ];
    }
}
