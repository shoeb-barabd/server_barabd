<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PresetRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function rowId(): ?int
    {
        $p = $this->route('preset');
        return is_object($p) ? $p->getKey() : ($p ? (int)$p : null);
    }

    public function rules(): array
    {
        $id = $this->rowId();

        return [
            'product_id'       => ['required','integer','exists:products,id'],
            'name'             => ['required','string','max:150'],
            'slug'             => ['required','string','max:150', Rule::unique('presets','slug')->ignore($id)],
            'config'           => ['required','array'],            // accept array; model casts to array
            'included_addons'  => ['nullable','array'],
            'sort_order'       => ['nullable','integer','min:0'],
            'is_featured'      => ['required','boolean'],
        ];
    }
}
