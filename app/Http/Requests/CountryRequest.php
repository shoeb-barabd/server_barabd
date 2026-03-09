<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * @method mixed route(string|null $key = null)
 * @method mixed input(string $key = null, mixed $default = null)
 * @method bool  filled(string $key)
 * @method bool  has(string $key)
 * @method void  merge(array $input)
 */


class CountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // later: gate/policy
    }

    public function rules(): array
    {
        $id = $this->route('country')?->id; // route model binding (edit/update)

        return [
            'name'       => ['required','string','max:100'],
            'iso2'       => [
                'required','alpha','size:2',
                Rule::unique('countries','iso2')->ignore($id),
            ],
            'iso3'       => ['nullable','alpha','size:3'],
            'phone_code' => ['nullable','string','max:8'],
            'is_active'  => ['nullable','boolean'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'iso2'      => strtoupper((string)$this->input('iso2')),
            'iso3'      => $this->filled('iso3') ? strtoupper((string)$this->input('iso3')) : null,
            'is_active' => $this->has('is_active') ? (bool)$this->input('is_active') : false,
        ]);
    }
}
