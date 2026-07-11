<?php

namespace App\Enums;

enum UserRole: string
{
    case Support = 'support';
    case Viewer = 'viewer';
}