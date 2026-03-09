<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** @method mixed route(string|null $key = null) */
class CurrencyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    // ⬇️ must be PUBLIC
    public function validationData(): array
    {
        $d = request()->all();
        $d['code']           = isset($d['code']) ? strtoupper((string)$d['code']) : null;
        $d['symbol']         = $d['symbol'] ?? null;
        $d['decimal_places'] = (int)($d['decimal_places'] ?? 2);
        $d['is_active']      = array_key_exists('is_active', $d) ? (bool)$d['is_active'] : false;
        return $d;
    }

    public function rules(): array
    {
        $id = $this->route('currency')?->id;
        return [
            'code' => [
                'required','alpha','size:3',
                Rule::unique('currencies','code')
                    ->ignore($this->route('currency')?->id)
                    ->where(fn ($q) => $q->whereNull('deleted_at')),
            ],
            'name'           => ['required','string','max:100'],
            'symbol'         => ['nullable','string','max:8'],
            'decimal_places' => ['required','integer','between:0,6'],
            'is_active'      => ['nullable','boolean'],
        ];
    }
}
