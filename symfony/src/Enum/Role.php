<?php

namespace App\Enum;

enum Role: string
{
    case user = 'ROLE_USER';
    case premium = 'ROLE_PREMIUM';
    case admin = 'ROLE_ADMIN';
}
