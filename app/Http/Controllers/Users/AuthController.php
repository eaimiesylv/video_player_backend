<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthFormRequest;
use App\Models\User;
use App\Services\UserService\UserService;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    protected UserService $userService;


    public function __construct(UserService $userService)
    {

        $this->userService = $userService;

    }

    public function logout(Request $request)
    {
        $accessToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($accessToken);
        $token->delete();

        return response()->json(['success' => true, 'message' => 'Logout successful'], 200);
    }


    public function login(AuthFormRequest $request)
    {




        $user = $this->userService->getUserByCredentials($request->all());



        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials', 'data' => []], 400);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password not correct', 'data' => []], 400);
        }

        $response = $this->userService->generateAuthResponse($user, 'Login successful');
        return response()->json($response, 201);


    }
}
