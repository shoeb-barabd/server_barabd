<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingCycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gate/role will be handled via middleware
    }

    public function rules(): array
    {
        $id = $this->route('billing-cycle')?->id;

        return [
            'key'         => ['required', 'string', 'max:20', 'unique:billing_cycles,key,' . $id],
            'name'        => ['required', 'string', 'max:50'],
            'months'      => ['required', 'integer', 'min:1'],
            'is_active'   => ['required', 'boolean'],
            'whmcs_cycle' => ['nullable', 'string', 'max:30'],
        ];
    }
}
