<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @mixin \Illuminate\Http\Request
 */
class ProductFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function featureId(): ?int
    {
        $param = $this->route('product_feature') ?? $this->route('product-feature');
        if (is_object($param) && method_exists($param, 'getKey')) {
            return (int) $param->getKey();
        }
        return $param !== null ? (int) $param : null;
    }

    public function rules(): array
    {
        $id        = $this->featureId();
        $productId = (int) $this->input('product_id');

        // Allowed input types according to your table examples
        $allowedInputTypes = ['number','boolean','text','select'];

        return [
            'product_id'   => ['required','integer','exists:products,id'],
            'key'          => [
                'required','string','max:100',
                Rule::unique('product_features','key')
                    ->ignore($id)
                    ->where(fn($q) => $q->where('product_id', $productId)),
            ],
            'label'        => ['required','string','max:150'],
            'input_type'   => ['required','string', Rule::in($allowedInputTypes)],
            'unit'         => ['nullable','string','max:20'],
            'min'          => ['nullable','numeric'],
            'max'          => ['nullable','numeric'],
            'step'         => ['nullable','numeric'],
            'options_json' => ['nullable','string'],   // keep as raw JSON string
            'is_required'  => ['required','boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'key.unique'       => 'This key already exists for the selected product.',
            'input_type.in'    => 'Input type must be one of: number, boolean, text, select.',
            'options_json.json'=> 'Options must be a valid JSON string.',
        ];
    }
}
