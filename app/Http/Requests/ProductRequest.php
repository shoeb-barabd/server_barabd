<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gate/role will be handled by middleware
    }

    public function rules(): array
    {
        $id = $this->route('product')?->id; // get the id for edit/update

        return [
            'category_id'      => ['required', 'exists:categories,id'],
            'name'             => ['required', 'string', 'max:100', Rule::unique('products', 'name')->ignore($id)],
            'slug'             => ['required', 'string', 'max:100', Rule::unique('products', 'slug')->ignore($id)],
            'icon_class'       => ['nullable', 'string', 'max:100'],
            'save_text'        => ['nullable', 'string', 'max:255'],
            'is_active'        => ['required', 'boolean'],
            'whmcs_product_id' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
