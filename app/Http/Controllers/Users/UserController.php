<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserFormRequest;
use App\Services\UserService\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }
    public function index()
    {
        return $this->userService ->getAllUsers();
    }
    public function store(UserFormRequest $request): JsonResponse
    {




        $response = $this->userService->createUser($request->all());


        if ($response['success']) {
            $user = $response['data'];

            $response = $this->userService->generateAuthResponse($user, 'Registration successful');
            return response()->json($response, 201);



        } else {

            return response()->json(['success' => false, "message" => $response['message'], "data" => []], 500);
        }
    }





}
