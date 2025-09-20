<?php

namespace App\Enums\AuditTrack;

enum ChangeType: string
{
    case Creation = 'creation';
    case Update = 'update';
    case Deletion = 'deletion';
    case Restoration = 'restoration';
}
