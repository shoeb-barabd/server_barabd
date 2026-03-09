<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $coupon = $this->route('coupon');
        $couponId = $coupon?->id ?? null;

        return [
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code,' . $couponId],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'discount_amount' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
