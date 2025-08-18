<?php

namespace App\Interfaces;

use App\Models\AuditTrack;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

interface TracksChanges
{
    public function getComparativeChanges(): array;

    public function auditTracks(): MorphMany;
}
