<?php

namespace App\Filament\Resources\Company\EmployeeResource\Pages;

use App\Filament\Resources\Company\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
