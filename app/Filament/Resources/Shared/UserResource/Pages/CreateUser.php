<?php

namespace App\Filament\Resources\Shared\UserResource\Pages;

use App\Filament\Resources\Shared\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
