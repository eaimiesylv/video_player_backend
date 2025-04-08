<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // public function handleGoogleCallback()
    // {

    //     try {
    //         $user = Socialite::driver('google')->stateless()->user();
    //         $user = \App\Models\User::select('id','role_id', 'type')->where('email', $user->email)->first();
    //         if($user){

    //             $data= [
    //                 'token'=>$user->createToken('api-token')->plainTextToken,
    //                 'role_id' => $user->role_id,
    //                 'type' => $user->type,
    //                 'data' => []
    //             ];
    //             return response()->json(['success' =>true, 'message'=>'Login successfully',"data"=>$data,],200);
    //         }
    //         return response()->json(['success' =>false, 'message'=>'You need to register to login',"data"=>[],],404);

    //     } catch (\Exception $e) {
    //         // Handle error
    //         return $e;

    //     }
    // }
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = \App\Models\User::select('id', 'role_id', 'type')->where('email', $googleUser->email)->first();

            if($user) {
                $token = $user->createToken('api-token')->plainTextToken;
                $frontendUrl = config('services.frontend_url'); // Define this in your config or .env

                // Redirect to frontend with token and user info as query parameters
                return redirect()->away("{$frontendUrl}/auth/callback?token={$token}&role_id={$user->role_id}&type={$user->type}");
            }

            // If user not found, redirect to frontend registration page
            $frontendUrl = config('services.frontend_url');
            return redirect()->away("{$frontendUrl}/register?email={$googleUser->email}");

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Google OAuth Callback Error: ' . $e->getMessage());

            // Redirect to frontend with an error message
            $frontendUrl = config('services.frontend_url');
            return redirect()->away("{$frontendUrl}/login?error=oauth_error");
        }
    }

}
