<?php

namespace App\Services\UserService;
use App\Services\UserService\OtpRepository;
use App\Events\WelcomeEmailSent;

class OtpService
{
    protected OtpRepository $otpRepository;
    

    public function __construct(OtpRepository $otpRepository)
    {
       
        $this->otpRepository = $otpRepository;

    }

    public  function createOtp($data){

        return $this->otpRepository->createOtp($data);
    }
    public function getOtpByUser($user, $otp){

        return $this->otpRepository->getOtpByUser($user, $otp);
    }
    public function getSentOtp($source){
       
        return $this->otpRepository->getSentOtp($source);
    }
   
    public function confirmSmsStatus($request)
    { 	
        return $this->otpRepository->confirmSmsStatus($request);
    }
    
   
   
}