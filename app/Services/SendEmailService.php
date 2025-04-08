<?php

namespace App\Services;

use App\Events\WelcomeEmailSent;

class SendEmailService
{
   
    public function sendWelcomeEmail($user,$resendHash=null, $sendType=null)
    {
       
        event(new WelcomeEmailSent($user, $resendHash, $sendType));
    }
}
