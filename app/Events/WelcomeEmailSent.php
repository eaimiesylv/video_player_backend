<?php

namespace App\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

//class WelcomeEmailSent implements ShouldQueue
class WelcomeEmailSent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $userDetail;
    public $emailType;

    public function __construct($user_detail, $emailType = null)
    {

        //these are the details are received from the controller and sent to the listeners


        $this->userDetail = $user_detail;

        $this->emailType = $emailType;




    }


}
