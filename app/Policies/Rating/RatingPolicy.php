<?php

namespace App\Policies\Rating;

use App\Models\Rating\Rating;
use App\Models\Shared\User;
use Illuminate\Auth\Access\Response;

class RatingPolicy
{
    public function view(User $user, Rating $rating): bool
    {
        $employeeId = request()->employeeId;

        return false;
    }
}
