<?php

namespace App\Listeners;

use App\Events\WelcomeEmailSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Crypt;

class SendWelcomeEmail
{
    //$event has access to all variables in the event class
    public function handle(WelcomeEmailSent $event)
    {

        $otp = isset($event->userDetail->otp) ? $event->userDetail->otp : null;

        $otherDetail = ['otp' => $otp,'user_data' => $event->userDetail];


        if($event->emailType == 'invoice') {

            // dd(auth()->user()->id);
            $emailRecipent = $event->userDetail['customers']['email'];
            // $otherDetail = $event->userDetail;



        } else {

            $emailRecipent = $event->userDetail->email;
        }


        try {
            // Check if 'otp' exists in the user detail before access
            // dd($otherDetail);

            Mail::to($emailRecipent)->send(new WelcomeMail($event->emailType, $otherDetail));

        } catch (\Throwable $e) {
            //throw $e;
            \Log::error('Error sending email', ['error' => $e->getMessage()]);
        }

    }
}
