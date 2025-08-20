<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use App\Enums\Permission as PermissionEnum;

/**
 * @property-read int $id
 *
 * @property string $name
 *
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read Collection<array-key, User> $users
 * @property-read Collection<array-key, Role> $roles
 */
class Permission extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::created(function (Permission $model) {
            // Add Permission to Admin role
            Role::findByEnum(RoleEnum::Admin)->permissions()->syncWithoutDetaching([$model->id]);
        });
    }

    protected $fillable = [
        'name'
    ];

    public static function createFromEnum(PermissionEnum $enum): Permission
    {
        return static::create([
            'name' => $enum->value,
        ]);
    }

    public static function deleteByEnum(PermissionEnum $enum): Permission
    {
        return static::query()->where('name', $enum->value)->delete();
    }

    public static function findByEnum(PermissionEnum $enum): ?Permission
    {
        return static::firstWhere('name', $enum->value);
    }

    /**
     * @param PermissionEnum[] $enums
     * @return Collection<array-key, self>
     */
    public static function findManyByEnums(array $enums): Collection
    {
        return self::query()
            ->whereIn('name', array_map(fn (PermissionEnum $enum) => $enum->value, $enums))
            ->get();
    }


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
