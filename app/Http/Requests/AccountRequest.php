<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @mixin \Illuminate\Http\Request
 * @method array all($keys = null)
 * @method void merge(array $input)
 * @method bool boolean(string $key, $default = false)
 */
class AccountRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['required','string','max:150'],
            'country_id'  => ['nullable','exists:countries,id'],
            'email'       => ['nullable','email','max:150'],
            'phone'       => ['nullable','string','max:50'],
            'website'     => ['nullable','string','max:150'],
            'address'     => ['nullable','string','max:500'],
            'is_active'   => ['nullable','boolean'],

            // keep existing columns too (so edit form can’t drop them)
            'type'            => ['nullable','in:individual,business'],
            'display_name'    => ['nullable','string','max:150'],
            'tax_id'          => ['nullable','string','max:150'],
            'status'          => ['nullable','in:active,suspended,prospect'],
            // billing_address stays json via seeder/UI (no change here)
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
