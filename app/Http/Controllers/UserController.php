<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q',''));

        $rows = User::query()
            ->when($q !== '', function ($qq) use ($q) {
                $qq->whereRaw("CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')) LIKE ?", ["%{$q}%"])
                   ->orWhere('email','like',"%{$q}%");
            })
            ->orderBy('first_name')->orderBy('last_name')
            ->paginate(15)->withQueryString();

        return view('back.users.index', [
            'users' => $rows,
            'q'     => $q,
        ]);
    }

    public function create()
    {
        return view('back.users.create', ['user' => new User]);
    }

    public function store(UserRequest $req)
    {
        $data = $req->validated();
        $user = User::create($data);

        $markVerified = isset($_POST['mark_verified']) && $_POST['mark_verified'] === '1';
        if ($markVerified) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        return redirect()->route('admin.users.index')->with('success','User created.');
    }

    public function update(UserRequest $req, User $user)
    {
        $data = $req->validated();
        if (empty($data['password'])) unset($data['password']);
        $user->update($data);

        $markVerified = isset($_POST['mark_verified']) && $_POST['mark_verified'] === '1';

        if ($markVerified && ! $user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        } elseif (! $markVerified && $user->email_verified_at) {
            $user->forceFill(['email_verified_at' => null])->save();
        }

        return redirect()->route('admin.users.index')->with('success','User updated.');
    }

    public function edit(User $user)
    {
        return view('back.users.edit', ['user' => $user]);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return back()->with('success','User deleted.');
        } catch (\Throwable $e) {
            return back()->with('error','Unable to delete user.');
        }
    }
}
