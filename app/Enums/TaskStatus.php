<?php

namespace App\Enums;

enum TaskStatus: string
{
    case NEW = 'new';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
}
