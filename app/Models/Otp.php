<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

//use App\Traits\SetCreatedBy;


class Otp extends Model
{
    //use SetCreatedBy;
    use HasUuids;
    use HasFactory;




    protected $fillable = [
        'id',
        'user_id',
        'otp_code',
        'expiry_time',
        'sms_status',
        'sent_via',
        'pending_email_or_phone_number'

    ];
}
