<?php

namespace App\Services\UserService;

use Illuminate\Support\Facades\DB;
use App\Models\Otp;
use Carbon\Carbon;
use App\Models\User;

class OtpRepository
{
    public function createOtp($data)
    {
        //dd($data);

        return Otp::UpdateOrCreate(
            ['user_id' => $data->id],
            [
                'user_id' => $data->id,
                'otp_code' => rand(100000, 999999),
                'expiry_time' => now()->addMinutes(5),
                'sms_status' => 'pending',
                'sent_via' => $data->sent_via,
                'pending_email_or_phone_number' => $data->sent_via,
            ]
        );

    }
    public function getOtpByUser($user, $otp)
    {


        return Otp::select('otp_code', 'expiry_time')
                    ->where('user_id', $user->id)
                    ->where('sent_via', $user->sent_via)
                    ->where('otp_code', $otp)
                    ->where('expiry_time', '>', Carbon::now())
                    ->orderBy('id', 'desc')
                    ->first();
    }
    public function getSentOtp($source)
    {

        return Otp::select('otp_code', 'sms_status')->where('sent_via', $source)
        ->orderBy('id', 'desc')
        ->first();
    }
    public function confirmSmsStatus($request)
    {
        $otp = Otp::where('sent_via', $request['To'])->first();
        if($otp) {

            $otp->update(['sms_status' => $request['SmsStatus']]);
        }
    }

    public function createconfirmEmailOrPhoneNumberUpdate($data)
    {

        try {
            // Fetch the OTP record that hasn't expired
            $otp = Otp::select('user_id', 'sent_via')
                        ->where('otp_code', $data['otp'])
                       ->where('expiry_time', '>', Carbon::now())
                       ->first();



            if (!$otp) {
                return null; // Return null if no valid OTP is found
            }

            // Determine if the OTP was sent via email or phone number
            $isEmail = filter_var($otp->sent_via, FILTER_VALIDATE_EMAIL);

            // Prepare the update data based on the OTP type
            $updateData = $isEmail
                ? ['email' => $otp->sent_via]
                : ['phone_number' => $otp->sent_via];

            // Update the user's email or phone number
            $user = User::where('id', $otp->user_id)->update($updateData);
            $user = User::select('email', 'phone_number')->where('id', $otp->user_id)->first();

            return $user;

        } catch (\Exception $e) {
            \Log::error('Error updating user email or phone number: ' . $e->getMessage());
            return null;
        }
    }
}
