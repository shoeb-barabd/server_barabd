<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function claim(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $coupon = Coupon::active()
            ->whereRaw('LOWER(code) = ?', [strtolower($validated['code'])])
            ->first();

        if (!$coupon) {
            return response()->json([
                'message' => 'Invalid or expired coupon.',
            ], 422);
        }

        return response()->json([
            'message' => 'Coupon claim successfully.',
            'coupon' => [
                'code' => $coupon->code,
                'title' => $coupon->title,
                'description' => $coupon->description,
                'discount_amount' => (float) $coupon->discount_amount,
            ]
        ]);
    }
}
