<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gate/role is handled by middleware
    }

    public function rules(): array
    {
        $id = $this->route('category')?->id;  // if editing, get the category id for update

        return [
            'name'    => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($id)],
            'slug'    => ['required', 'string', 'max:100', Rule::unique('categories', 'slug')->ignore($id)],
            'is_active'=> ['required', 'boolean'],
        ];
    }
}
