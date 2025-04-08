<?php

namespace App\Enums;

enum AppointmentStatus:int
{
    case CREATED = 0;
    case BOOKED = 1;
    case IN_ROUTE = 2;
    case ACTIVE = 3;
    case COMPLETED = 4;
    case CANCELLED = 5;
}
