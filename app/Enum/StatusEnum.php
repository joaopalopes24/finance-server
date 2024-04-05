<?php

namespace App\Enum;

enum StatusEnum: int
{
    case PENDING = 1;
    case PROCESSING = 2;
    case CANCELED = 3;
    case COMPLETED = 4;
}
