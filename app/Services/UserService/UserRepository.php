<?php

namespace App\Services\UserService;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserRepository
{
    public function getUserByCredentials($request)
    {


        return User::where('username', $request['username'])->first();

    }


    public function createUser(array $data)
    {




        try {

            return User::Create($data);


        } catch (\Exception $e) {

            \Log::info($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Registration error'], 500);
        }
    }


}
