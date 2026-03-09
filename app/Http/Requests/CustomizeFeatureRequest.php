<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomizeFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function featureId(): ?int
    {
        $param = $this->route('customize_feature') ?? $this->route('customize-feature');
        if (is_object($param) && method_exists($param, 'getKey')) {
            return (int) $param->getKey();
        }
        return $param !== null ? (int) $param : null;
    }

    public function rules(): array
    {
        $id         = $this->featureId();
        $categoryId = (int) $this->input('category_id');

        $allowedInputTypes = ['number', 'boolean', 'select'];

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'key' => [
                'required', 'string', 'max:100',
                Rule::unique('customize_features', 'key')
                    ->ignore($id)
                    ->where(fn($q) => $q->where('category_id', $categoryId)),
            ],
            'label'       => ['required', 'string', 'max:150'],
            'input_type'  => ['required', Rule::in($allowedInputTypes)],
            'unit'        => ['nullable', 'string', 'max:20'],
            'min'         => ['nullable', 'numeric'],
            'max'         => ['nullable', 'numeric'],
            'step'        => ['nullable', 'numeric'],
            'options_json'=> ['nullable', 'json'],
            'is_required' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'key.unique'      => 'This key already exists for the selected category.',
            'input_type.in'   => 'Input type must be number, boolean, or select.',
            'options_json.json' => 'Options must be a valid JSON string.',
        ];
    }
}
