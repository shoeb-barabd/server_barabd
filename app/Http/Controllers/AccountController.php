<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q',''));

        $rows = Account::query()
            ->with(['country'])
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('name','like',"%$q%")
                ->orWhere('email','like',"%$q%")
                ->orWhere('phone','like',"%$q%");
            })
            ->orderByDesc('id') // 👈 show recently added first
            ->paginate(15)
            ->withQueryString();

        return view('back.accounts.index', [
            'accounts' => $rows,
            'q'        => $q,
        ]);
    }

    public function create()
    {
        return view('back.accounts.create', [
            'account'   => new Account,
            'countries' => Country::orderBy('name')->get(),
        ]);
    }

    public function store(AccountRequest $req)
    {
        Account::create($req->validated());
        return redirect()->route('admin.accounts.index')->with('success','Account created.');
    }

    public function edit(Account $account)
    {
        return view('back.accounts.edit', [
            'account'   => $account,
            'countries' => Country::orderBy('name')->get(),
        ]);
    }

    public function update(AccountRequest $req, Account $account)
    {
        $account->update($req->validated());
        return redirect()->route('admin.accounts.index')->with('success','Account updated.');
    }

    public function destroy(Account $account)
    {
        try {
            $account->delete();
            return back()->with('success','Account deleted.');
        } catch (\Throwable $e) {
            return back()->with('error','Unable to delete account (in use).');
        }
    }
}
