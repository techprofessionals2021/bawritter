<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Junaidnasir\Larainvite\Facades\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterApiController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
   
        $validator = Validator::make($request->all(), [

           'first_name' => ['required', 'string', 'max:255'],
           'last_name' => ['required', 'string', 'max:255'],
           'phone_number' => ['required'],
           'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
           'password' => ['required', 'string', 'min:1'],
        ]);

        if ($validator->fails()) {
            return validationError($validator);
        }

        $response =  DB::transaction(function () use ($request) {

            $user = User::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'phone_number' => $request['phone_number'],
                'password' => Hash::make($request['password']),
            ]);

            $token = $user->createToken('API Token');

            return [

                'token' => $token->plainTextToken,
                'first_name'=>$user->first_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'last_name'=>$user->last_name,
            ];
        });

        $data = [
            'token' => $response['token'],
            'userInfo' => [
                'firstName' => $response['first_name'],
                'lastName' => $response['last_name'],
                'email' => $response['email'],
                'phone_number' => $response['phone_number'],
            ]
        ];

        return apiResponseSuccess($data, 'Registered successfully');
    }

}
