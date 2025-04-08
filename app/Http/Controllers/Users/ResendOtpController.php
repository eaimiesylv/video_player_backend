<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\UserService\OtpService;
use App\Services\UserService\UserService;
use App\Services\SendEmailService;
use App\Events\WelcomeEmailSent;

class ResendOtpController extends Controller
{
    protected OtpService $otpService;
    protected UserService $userService;
    protected SendEmailService $sendEmailService;

    public function __construct(OtpService $otpService, UserService $userService, SendEmailService $sendEmailService)
    {
        $this->otpService = $otpService;
        $this->userService = $userService;
        $this->sendEmailService = $sendEmailService;

    }

    public function store(Request $request): JsonResponse
    {
        // dd($request->all());
        $request->validate([

           'phone_number' => 'required_without:email|string',
           'email'        => 'required_without:phone_number|email|exists:users'
        ]);

        $formatedNumber = $this->userService->formatNumber($request);
        if($formatedNumber == false) {
            return response()->json(['success' => false, "message" => "Invalid Number format", "data" => []], 500);
        }
        $request->merge(['phone_number' => $formatedNumber]);
        if (!is_null($request->phone_number)) {

            $user = $this->userService->getUserByPhoneNumber($request->phone_number);
            $user['sent_via'] = $request->phone_number;

        } else {
            $user = $this->userService->getUserByEmail($request->email);
            $user['sent_via'] = $request->email;
        }
        $userOtp = $this->otpService->createOtp($user);
        $user['otp'] = $userOtp->otp_code;

        if (!is_null($request->phone_number)) {

            // Uncomment this line if you want to actually send the SMS
            // $smsResponse = $this->sendOtpService->sendSms($user->phone_number, $user['otp']);
            return response()->Json(["data" => "Verify your phone number using the otp sent to $request->phone_number"], 200);
        } else {
            event(new WelcomeEmailSent($user, "retail_customer"));
            return response()->Json(["data" => "Verify your email using the otp sent to you"], 200);
        }



    }

}
