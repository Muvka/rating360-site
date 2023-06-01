<?php

namespace App\Traits\User;

use App\Models\User\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin() :bool {
        return $this->roles->contains('slug', 'administrator');
    }

    public function isManager() :bool {
        return $this->roles->contains('slug', 'manager');
    }

    public function hasRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }

        return false;
    }
}
