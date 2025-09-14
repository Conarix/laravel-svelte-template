<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum Permission: string
{
    use EnumTrait;

    case USERS_VIEW = "users_view";
    case USERS_CREATE = "users_create";
    case USERS_EDIT = "users_edit";
    case USERS_DELETE = "users_delete";

    case USERS_IMPERSONATE = "users_impersonate";

    public function title(): string
    {
        return match ($this) {
            self::USERS_VIEW => "View Users",
            self::USERS_CREATE => "Create User",
            self::USERS_EDIT => "Edit User",
            self::USERS_DELETE => "Delete User",
            self::USERS_IMPERSONATE => "Impersonate User",
        };
    }
}
