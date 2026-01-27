<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function getGoogleSignInUrl()
    {
        return Socialite::driver('google')->redirect();
    }

    public function loginCallback(Request $request)
    {
        $user = Socialite::driver('google')->stateless()->user();

        $findUser = User::where('google_id', $user->id)->first();
        if($findUser) {
            Auth::login($findUser);
            return redirect()->intended('/');
        }else{
            $findEmail = User::where('email', $user->email)->first();
            if($findEmail) {
                $findEmail->google_id = $user->id;
                $findEmail->save();
                Auth::login($findEmail);
                return redirect()->intended('/');
            }
            $newUser = new User();
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->google_id = $user->id;
            $newUser->avatar = $user->avatar;
            $newUser->password = bcrypt($user->id);
            $newUser->save();
            Auth::login($newUser);
            return redirect()->intended('/');
        }

    }
}
