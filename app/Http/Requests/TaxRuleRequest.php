<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxRuleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'country_id'    => ['required','exists:countries,id'],
            'tax_name'      => ['required','string','max:50'],
            'rate_percent'  => ['required','numeric','between:0,99.99'],
            'is_inclusive'  => ['nullable','boolean'],
            'effective_from'=> ['required','date'],
            'effective_to'  => ['nullable','date','after:effective_from'],
            'notes'         => ['nullable','string','max:500'],
        ];
    }

    public function validationData(): array
    {
        $d = request()->all();
        $d['is_inclusive'] = array_key_exists('is_inclusive',$d) ? (bool)$d['is_inclusive'] : false;
        return $d;
    }
}
