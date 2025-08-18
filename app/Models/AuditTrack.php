<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditTrack extends Model
{
    protected $fillable = [
        'user_id',
        'trackable_type',
        'trackable_id',
        'creation',
        'changes',
    ];

    protected $with = [
        'user',
    ];

    protected $hidden = [
        'trackable_type',
        'trackable_id',
    ];

    protected $casts = [
        'changes' => 'json'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }
}
