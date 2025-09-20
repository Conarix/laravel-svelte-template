<?php

use App\Enums\AuditTrack\ChangeType;
use App\Models\AuditTrack;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

if (! function_exists('create_audit_track')) {
    /**
     * @param array<string, mixed> $changes
     */
    function create_audit_track(Model $model, ChangeType $type, array $changes): AuditTrack
    {
        if ($type !== ChangeType::Update) {
            $changes = [];
        }

        return AuditTrack::create([
            'user_id' => Auth::id(),
            'trackable_type' => get_class($model),
            'trackable_id' => $model->getKey(),
            'type' => $type,
            'changes' => $changes,
        ]);
    }
}
