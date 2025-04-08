<?php

namespace App\Services\UserService;

use Illuminate\Support\Facades\DB;
use App\Services\UserService\UserRepository;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;

class UserService
{
    protected UserRepository $userRepository;


    public function __construct(
        UserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;

    }
    public function getUserByCredentials(array $data)
    {


        return $this->userRepository->getUserByCredentials($data);


    }

    public function createUser(array $all)
    {


        $user = $this->userRepository->createUser($all);
        if($user) {



            return ['success' => true, 'message' => 'registration successful', 'data' => $user];
        }
        return ['success' => false, 'message' => 'unknown error'];

    }
    public function generateAuthResponse(User $user, string $message): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => [
                'token' => $user->createToken('api-token')->plainTextToken,
                'data' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ]
            ]
        ];
    }



}
