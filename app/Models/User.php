<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Traits\ConvertUserTypeTrait;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\SetCreatedBy;
use Carbon\Carbon;

class User extends Authenticatable
{
    use SetCreatedBy;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use ConvertUserTypeTrait;





    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'phone_number',
        'password',
        'email_verified_at',



    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [


        'email_verified_at',
        'updated_at'



    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

        'created_at' => 'datetime:d-m-y H:i:s',
        'updated_at' => 'datetime:d-m-y H:i:s',

        'password' => 'hashed',

    ];









}
