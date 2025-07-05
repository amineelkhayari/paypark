<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\AppUsers;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        $user = AppUsers::Where([['email', $socialUser->getEmail()]])->first();


        //$user = AppUsers::where('email', $socialUser->getEmail())->first();
        // dd($user);
        if (!$user) {
            $user = AppUsers::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt("12345678"),
                'image' => $socialUser->getAvatar(),
            ]);
        }
        Auth::guard('appuser')->login($user);
        return redirect('/');
    }
}
