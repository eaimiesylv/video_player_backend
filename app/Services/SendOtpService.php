<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class SendOtpService
{
    // Method to send OTP
    public function sendOtp(string $phoneNumber, string $otp): JsonResponse
    {
        // working sms
        // dd($phoneNumber);
        $data = [
            'api_key' => env('TERMII_SMS_KEY'), // Fetch API key from .env file
            'to' => $phoneNumber,  // The recipient's phone number
            'from' => 'Afrikobo',  // Or any other sender name you wish
            'sms' => "$otp",
            'type' => 'plain',
            'channel' => 'generic',
        ];
        // $data = [
        //     'api_key' => env('TERMII_SMS_KEY'), // Fetch API key from .env file
        //     'to' => 2348103141818,  // The recipient's phone number
        //     'device_id' => 'Afrikobo',  // Or any other sender name you wish
        //     'sms' => "12345",
        //     'type' => 'plain',
        //     'channel' => 'whatsapp',
        // ];

        // Send the POST request to Termii API using Laravel's HTTP client
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://v3.api.termii.com/api/sms/send', $data);

        // Check if the response is successful
        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully.',
                'data' => $response->json(),  // Include the API response data
            ]);
        } else {
            // If the request fails, return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP.',
                'error' => $response->json(),  // Include the error details from the API
            ], $response->status());
        }
    }
}
