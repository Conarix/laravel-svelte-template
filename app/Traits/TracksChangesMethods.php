<?php

namespace App\Traits;

use App\Interfaces\TracksChanges;
use App\Models\AuditTrack;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use stdClass;

/**
 * @mixin Model
 */
trait TracksChangesMethods
{
    const array IGNORE_PROPERTY_UPDATES = [
        'updated_at',
    ];

    public static function bootTracksChangesMethods(): void
    {
        if (Auth::check()) {
            static::created(function (TracksChanges $model) {
                $model->auditTracks()->create([
                    'user_id' => Auth::id(),
                    'creation' => true,
                    'changes' => new stdClass(),
                ]);
            });

            static::updated(function (TracksChanges $model) {
                $changes = $model->getComparativeChanges();

                if (empty($changes)) return;

                $model->auditTracks()->create([
                    'user_id' => Auth::id(),
                    'creation' => false,
                    'changes' => $changes,
                ]);
            });
        }
    }

    public function getComparativeChanges(): array
    {
        $changes = $this->getChanges();

        $changes = array_filter(
            $changes,
            fn ($key) => !in_array($key, self::IGNORE_PROPERTY_UPDATES),
            ARRAY_FILTER_USE_KEY
        );

        return $changes;
    }

    public function auditTracks(): MorphMany
    {
        return $this->morphMany(AuditTrack::class, 'trackable')
            ->orderBy('created_at', 'desc');
    }
}
