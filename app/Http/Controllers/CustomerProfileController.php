<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Country;

class CustomerProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name'   => ['required','string','max:100'],
            'last_name'    => ['nullable','string','max:100'],
            'company_name' => ['nullable','string','max:255'],
            'email'        => ['required','email','max:255'],
            'phone'        => ['nullable','string','max:50'],
            'address_1'    => ['nullable','string','max:255'],
            'address_2'    => ['nullable','string','max:255'],
            'city'         => ['nullable','string','max:120'],
            'state'        => ['nullable','string','max:120'],
            'postal_code'  => ['nullable','string','max:60'],
        ]);

        // Update user core fields
        $user->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'] ?? null,
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
        ]);

        // Ensure account exists
        $account = $user->account;
        if (! $account) {
            $country = Country::first();
            if (! $country) {
                return back()->with('error', 'Please configure at least one country before saving profile.');
            }

            $account = Account::create([
                'type'          => 'individual',
                'name'          => $validated['company_name'] ?: trim(($validated['first_name']).' '.($validated['last_name'] ?? '')),
                'display_name'  => $validated['company_name'] ?: null,
                'country_id'    => $country->id,
                'status'        => 'active',
                'email'         => $validated['email'],
                'phone'         => $validated['phone'] ?? null,
                'address'       => $validated['address_1'] ?? null,
                'is_active'     => true,
                'billing_address' => [
                    'line1'        => $validated['address_1'] ?? null,
                    'line2'        => $validated['address_2'] ?? null,
                    'city'         => $validated['city'] ?? null,
                    'state'        => $validated['state'] ?? null,
                    'postal_code'  => $validated['postal_code'] ?? null,
                ],
            ]);
            $user->account()->associate($account);
            $user->save();
        } else {
            $account->update([
                'name'          => $validated['company_name'] ?: ($account->name ?: $user->name),
                'display_name'  => $validated['company_name'] ?: $account->display_name,
                'email'         => $validated['email'],
                'phone'         => $validated['phone'] ?? $account->phone,
                'address'       => $validated['address_1'] ?? $account->address,
                'billing_address' => array_merge($account->billing_address ?? [], [
                    'line1'        => $validated['address_1'] ?? null,
                    'line2'        => $validated['address_2'] ?? null,
                    'city'         => $validated['city'] ?? null,
                    'state'        => $validated['state'] ?? null,
                    'postal_code'  => $validated['postal_code'] ?? null,
                ]),
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
