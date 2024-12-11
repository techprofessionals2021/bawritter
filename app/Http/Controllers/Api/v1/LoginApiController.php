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
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return validationError($validator); // Use the custom validationError function
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('Token');
            $data = [
                'user' => $user->email,
                'token' => $token->plainTextToken,
                'user_id' => $user->id,
                'user_name' => $user->first_name ." ". $user->last_name,
            ];

            return apiResponseSuccess($data, 'Login Successfull!');
        }

        return responseError('The provided credentials do not match our records.', 'Authentication Error', 401);
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

        // Check if the validation fails
        if ($validator->fails()) {
            return validationError($validator); // Use the custom validationError function
        }

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
        // Search for the OTP entry using the provided email
        $password_reset_column = DB::table('password_resets')->where([
            'email' => $request->email,
        ])->first();

        // Check if the entry exists
        if (!$password_reset_column) {
            // No entry found for the provided email
            return responseError(null, 'No verification data found. Please initiate the process again.', 404);
        }

        // Validate the provided token
        if ($password_reset_column->token == $request->token) {
            // OTP is correct
            return apiResponseSuccess([
                'success' => true,
            ], 'OTP verified successfully.');
        } else {
            // OTP mismatch
            return responseError(null, 'Invalid OTP. Please try again.', 401);
        }
    }
    public function resetPassword(Request $request)
    {

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',  // Ensure the email is valid
            'password' => 'required|string|min:8|confirmed',  // Ensure the password is confirmed
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return validationError($validator);
        }

        // Attempt to retrieve the password reset entry from the database
        $password_reset_entry = DB::table('password_resets')->where('email', $request->email)->first();

        // Check if a password reset entry exists for the given email
        if (!$password_reset_entry) {
            return responseError(null, 'No password reset request found for this email.', 404);
        }

        // Password reset entry exists, proceed to update user's password
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Delete the password reset entry after successful password update
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Return a success response
        return apiResponseSuccess([
            'success' => true,
        ], 'Password updated successfully.');
    }
}
