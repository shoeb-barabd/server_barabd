<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddOnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function addonId(): ?int
    {
        $param = $this->route('add_on') ?? $this->route('add-on');
        if (is_object($param) && method_exists($param, 'getKey')) return (int) $param->getKey();
        return $param !== null ? (int) $param : null;
    }

    public function rules(): array
    {
        $id = $this->addonId();

        return [
            'category_id'   => ['required','integer','exists:categories,id'],
            'key'           => ['required','string','max:100', Rule::unique('add_ons','key')->ignore($id)],
            'label'         => ['required','string','max:150'],
            'description'   => ['nullable','string','max:1000'],
            'unit_type'     => ['required','string', Rule::in(['one_time','recurring'])],
            'is_qty_based'  => ['required','boolean'],
            'max_qty'       => ['required','integer','min:1','max:65535'],
            'is_active'     => ['required','boolean'],
        ];
    }
}
