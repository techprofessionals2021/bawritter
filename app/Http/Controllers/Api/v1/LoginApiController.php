<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Mail\otp;
use App\Mail\SendOtp;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LoginApiController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('Token');
            $data = [
                'user' => $user->email,
                'token' => $token->plainTextToken
            ];

            return apiResponseSuccess($data, 'Login Successfull!');
        }

    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();

            return apiResponseSuccess([
                'success' => true,
            ], 'Logout successfulLy');
        }

        return responseError([
            'success' => false,
        ], 'User not authenticated');

    }


    public function forgotPassword(Request $request)
    {
        $otp = mt_rand(1000, 9999);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        Mail::to($request->email)->send(
            new SendOtp(

                'OTP',
                'emails.otp',
                [
                    'heading' => "Best Assignment Writer",
                    'content' => "Verify your Email Address Your Otp is.",
                    'btn' => [
                        'text' => $otp
                    ]
                ]
            )
        );

        DB::table('password_resets')
            ->where('email', $request->email)->delete();

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $otp,
            'created_at' => now()
        ]);

        return apiResponseSuccess([
            'user' => $request->email,
            'token' => $otp,
            'endPoint' => route('verify.otp'),
        ], 'We have emailed your password reset link!');
    }

    public function verifyOtp(Request $request)
    {
        $password_reset_column = DB::table('password_resets')->where([
            'email' => $request->email,
        ])->first();


        if ($password_reset_column) {


            if ($password_reset_column->token == $request->token) {

                return apiResponseSuccess([
                    'success' => true,

                ], 'Otp verified');
            }
        }
    }
    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $password_reset_column = DB::table('password_resets')->where([
            'email' => $request->email,

        ])->first();


        if ($password_reset_column) {
            User::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where([
                'email' => $request->email,

            ])->delete();
            return apiResponseSuccess([
                'success' => true,
            ], 'Password Updated Successfully');
        }
    }

}


