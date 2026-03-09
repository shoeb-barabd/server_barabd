<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @mixin \Illuminate\Http\Request
 * @method mixed route(string $key = null, $default = null)
 * @method void merge(array $input)
 * @method bool boolean(string $key, $default = false)
 * @method mixed input(string $key = null, $default = null)
 */
class ContactRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id         = $this->route('contact')?->id;
        $accountId  = $this->input('account_id');

        return [
            'account_id'            => ['required','exists:accounts,id'],
            'first_name'            => ['required','string','max:150'],
            'last_name'             => ['nullable','string','max:150'],
            'email'                 => [
                'required','email','max:150',
                Rule::unique('contacts','email')
                    ->where(fn($q) => $q->where('account_id', $accountId))
                    ->ignore($id),
            ],
            'phone'                 => ['nullable','string','max:50'],
            'designation'           => ['nullable','string','max:100'],
            'is_primary'            => ['nullable','boolean'],
            'notify_flags'          => ['nullable','array'],
            'notify_flags.billing'  => ['sometimes','boolean'],
            'notify_flags.support'  => ['sometimes','boolean'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_primary'   => $this->boolean('is_primary'),
            'notify_flags' => [
                'billing' => $this->boolean('notify_flags.billing'),
                'support' => $this->boolean('notify_flags.support'),
            ],
        ]);
    }
}
