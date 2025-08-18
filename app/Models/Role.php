<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Enums\Role as RoleEnum;

/**
 * @property-read int $id
 *
 * @property string $name
 *
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read Collection<array-key, User> $users
 * @property-read Collection<array-key, Permission> $permissions
 */
class Role extends Model
{
    protected $fillable = [
        'name'
    ];

    public static function createFromEnum(RoleEnum $enum): Role
    {
        return static::create([
            'name' => $enum->value,
        ]);
    }

    public static function findByEnum(RoleEnum $enum): ?Role
    {
        return static::firstWhere('name', $enum->value);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
