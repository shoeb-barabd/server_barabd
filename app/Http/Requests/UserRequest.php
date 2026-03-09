<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = request()->route('user')?->id;   // avoid Intelephense warnings

        return [
            'first_name' => ['required','string','max:150'],
            'last_name'  => ['nullable','string','max:150'],
            'email'      => ['required','email','max:150', Rule::unique('users','email')->ignore($id)],
            // password required on create; optional on update; confirm in form
            'password'   => [$id ? 'nullable' : 'required','string','min:6','confirmed'],
        ];
    }
}
