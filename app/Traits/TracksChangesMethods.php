<?php

namespace App\Traits;

use App\Enums\AuditTrack\ChangeType;
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
        'deleted_at',
    ];

    public static function bootTracksChangesMethods(): void
    {
        if (Auth::check()) {
            static::created(function (TracksChanges $model) {
                $model->auditTracks()->create([
                    'user_id' => Auth::id(),
                    'type' => ChangeType::Creation,
                    'changes' => new stdClass(),
                ]);
            });

            static::updated(function (TracksChanges $model) {
                $changes = $model->getComparativeChanges();

                if (empty($changes)) return;

                $model->auditTracks()->create([
                    'user_id' => Auth::id(),
                    'type' => ChangeType::Update,
                    'changes' => $changes,
                ]);
            });

            static::deleted(function (TracksChanges $model) {
                $model->auditTracks()->create([
                    'user_id' => Auth::id(),
                    'type' => ChangeType::Deletion,
                    'changes' => new stdClass(),
                ]);
            });

            if (method_exists(static::class, 'restored')) {
                static::restored(function (TracksChanges $model) {
                    $model->auditTracks()->create([
                        'user_id' => Auth::id(),
                        'type' => ChangeType::Restoration,
                        'changes' => new stdClass(),
                    ]);
                });
            }
        }
    }

    public function getComparativeChanges(): array
    {
        return collect($this->getChanges())
            ->filter(fn ($v, $k) => !in_array($k, static::IGNORE_PROPERTY_UPDATES))
            ->map(
                fn ($v, $k) => $this->isGuarded($k) || in_array($k, $this->getHidden())
                    ? "Updated " . str($k)->headline()
                    : $v
            )->toArray();
    }

    public function auditTracks(): MorphMany
    {
        return $this->morphMany(AuditTrack::class, 'trackable')
            ->orderBy('created_at', 'desc');
    }
}
