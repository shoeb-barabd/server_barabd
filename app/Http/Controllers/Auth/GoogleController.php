<?php
// app/Http/Controllers/Auth/GoogleController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->scopes(['email','profile'])->redirect();
    }

    public function callback()
    {
         $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')
        ->stateless()   // <-- যোগ করো
        ->user();

        $email = $googleUser->getEmail();
        if (!$email) {
            return redirect()->route('login')->with('error','Google account has no public email.');
        }

        return DB::transaction(function () use ($googleUser, $email) {
            $user = User::where('email', $email)->first();

            // নাম ভাঙা (Google থেকে ফার্স্ট/লাস্ট যদি থাকে)
            $given  = data_get($googleUser->user, 'given_name') ?: Str::before($email, '@');
            $family = data_get($googleUser->user, 'family_name') ?: '';

            // যেসব ফিল্ড নিশ্চিতভাবেই রাখা যায়
            $attrs = [
                'email'             => $email,
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'password'          => Hash::make(Str::random(32)),
            ];

            // টেবিলে যেসব কলাম আছে, সেগুলোকেই সেট করো (রানটাইম-সেফ)
            if (Schema::hasColumn('users', 'name'))       $attrs['name']       = trim($given.' '.$family);
            if (Schema::hasColumn('users', 'first_name')) $attrs['first_name'] = $given;
            if (Schema::hasColumn('users', 'last_name'))  $attrs['last_name']  = $family;
            if (Schema::hasColumn('users', 'role'))       $attrs['role']       = 'user';
            if (Schema::hasColumn('users', 'is_active'))  $attrs['is_active']  = 1;
            if (Schema::hasColumn('users', 'status'))     $attrs['status']     = 1;

            if (!$user) {
                $user = User::create($attrs);
            } else {
                // আপডেট—ফাঁকা থাকলে ভরো
                $update = [
                    'google_id' => $user->google_id ?: $attrs['google_id'],
                    'avatar'    => $attrs['avatar'] ?: $user->avatar,
                ];
                if (Schema::hasColumn('users','first_name') && !$user->first_name) $update['first_name'] = $given;
                if (Schema::hasColumn('users','last_name')  && !$user->last_name)  $update['last_name']  = $family;
                if (Schema::hasColumn('users','name')       && !$user->name)       $update['name']       = trim($given.' '.$family);
                if (!$user->email_verified_at) $update['email_verified_at'] = now();

                $user->forceFill($update)->save();
            }

            Auth::login($user, remember: true);
            return redirect()->intended(route('index'));
        });
    }
}
