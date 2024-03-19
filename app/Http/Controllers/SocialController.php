<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{

    public function redirectToGoogle()
{
    try {
        return Socialite::driver('google')->redirect();
    } catch (\Exception $e) {
        // Handle exception
        return redirect('/login')->with('error', 'Google authentication failed');
    }
}

public function handleGoogleCallback()
{
    try {
        $user = Socialite::driver('google')->user();

        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {

            auth()->login($existingUser);
        } else {
            $firstName = $user->user['given_name'] ?? 'DefaultFirstName';
            $lastName = $user->user['family_name'] ?? 'DefaultLastName';

            $newUser = new User();
            $newUser->first_name = $firstName;
            $newUser->last_name = $lastName;
            $newUser->email = $user->email;
            $newUser->password = bcrypt($user->id);
            $newUser->save();

            // Log in the newly created user
            auth()->login($newUser);
        }

        return redirect('dashboard');
    }  catch (\Exception $e) {

        return redirect('/login')->with('error', 'Google authentication');
    }
}
// faceboook login or sign in
public function redirectFacebook()
    {

        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        try {

            $user = Socialite::driver('facebook')->user();
            // .driver('facebook')->stateless()->user()
            //    dd($user);
            $finduser = User::where('facebook_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                return redirect()->intended('dashboard');

            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    //'facebook_id'=> $user->id,
                    'password' => $user->id,
                ]);

                Auth::login($newUser);

                return redirect()->intended('dashboard');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

}
// https://dev.to/shanisingh03/how-to-login-with-facebook-in-laravel-124e   login with facebook in laravel docs
