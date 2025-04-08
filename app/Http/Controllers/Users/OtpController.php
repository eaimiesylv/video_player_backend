<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\UserService\OtpService;
use App\Services\UserService\UserService;

class OtpController extends Controller
{
    protected OtpService $otpService;
    protected UserService $userService;

    public function __construct(OtpService $otpService, UserService $userService)
    {
        $this->otpService = $otpService;
        $this->userService = $userService;

    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
           'phone_number' => 'required_without:email|string',
           'otp'          => 'required|integer',
           'email'        => 'required_without:phone_number|email|exists:users'
        ]);

        $formatedNumber = $this->userService->formatNumber($request);

        if($formatedNumber === false) {
            return response()->json(['success' => false, "message" => "Invalid Number format", "data" => []], 500);
        }
        $request->merge(['phone_number' => $formatedNumber]);

        // dd($request->toArray());
        if (!empty($request->phone_number)) {

            $user = $this->userService->getUserByPhoneNumber($request->phone_number);
            $user['sent_via'] = $request->phone_number;
        } else {

            $user = $this->userService->getUserByEmail($request->email);
            $user['sent_via'] = $request->email;
        }

        $userOtp = $this->otpService->getOtpByUser($user, $request->otp);
        if($userOtp) {

            $this->userService->verifyUserByUuid($user->id);
            $data = [
               'token' => $user->createToken('api-token')->plainTextToken,
               'role_id' => $user->role_id,
               'type' => $user->type,
               'data' => [
                   'id' => $user["id"],
                   'first_name' => $user["first_name"],
                   'last_name' => $user["last_name"],
                   'phone_number' => $user["phone_number"],
                   'profile_status' => $user["profile_status"]
               ]
             ];
            return response()->json(['success' => true, 'message' => 'OTP successfully verified',"data" => $data,], 200);
        }
        return response()->json(['success' => false, 'message' => 'This OTP has either expire or is invalid', "data" => ''], 401);


    }
    public function show($request): JsonResponse
    {


        $data = $this->otpService->getSentOtp($request);
        if($data) {

            return response()->json(['success' => true, "data" => $data,], 200);
        }
        return response()->json(['success' => false, "message" => 'No record found', "data" => $data,], 404);


    }

    public function confirmSmsStatus(Request $request)
    {


        $this->otpService->confirmSmsStatus($request);


    }

}
