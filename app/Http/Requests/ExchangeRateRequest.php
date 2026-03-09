<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ExchangeRateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function validationData(): array
    {
        $d = request()->all();
        $d['base_currency_code']  = strtoupper((string)($d['base_currency_code']  ?? ''));
        $d['quote_currency_code'] = strtoupper((string)($d['quote_currency_code'] ?? ''));
        return $d;
    }

    public function rules(): array
    {
        return [
            'base_currency_code'  => ['required','alpha','size:3'],
            'quote_currency_code' => ['required','alpha','size:3','different:base_currency_code'],
            'rate'                => ['required','numeric','gt:0'],
            'valid_from'          => ['required','date'],
            'valid_to'            => ['nullable','date','after:valid_from'],
        ];
    }
}
