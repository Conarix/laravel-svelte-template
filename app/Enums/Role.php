<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum Role: string
{
    use EnumTrait;

    case Admin = "admin";
    case User = "user";

    public function title(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::User => 'User',
        };
    }
}
